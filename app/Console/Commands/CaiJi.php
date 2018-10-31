<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use QL\QueryList;

class CaiJi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caiji';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集大海中文';

    //自定义变量
    private $caiji_url = '';
    private $sleepTime = 10;
    private $baseSite = 'https://www.dhzw.org';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->caiji_url = 'https://www.dhzw.org/sort1/1.html';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //采集列表页
        $rules_1 = [
            'link' => ['.l ul li .s2 a','href'],
            'author' => ['.l ul li .s4', 'text'],
            'novel' => ['.l ul li .s2','text'],
            'category' => ['.l ul li .s1','text']
        ];
        $ql = QueryList::get($this->caiji_url)->rules($rules_1)->encoding('UTF-8','GB2312')->removeHead()->query();

        $linkDatas = $ql->getData();
//        print_r($linkDatas);
//        exit;
        //print_r($linkDatas[0]);



        if(!empty($linkDatas))
        {
            //----foreaech($linkDates as $link){}
            for($p=0;$p<=count($linkDatas)-1;$p++)
            {
                //$authorName = $linkDatas[0]['author'];
                //$novelName = $linkDatas[0]['novel'];
                //采集内容页
                $rules_2 = [
                    'chaptername' => ['#list dl dd','text'],
                    'chapterlink' => ['#list dl dd a','href'],
                    'photo' => ['#fmimg img','src'],
                    'intro' => ['.intro','html']
                ];
                //$ql = QueryList::get($linkDatas[0]['link'])->rules($rules_2)->encoding('UTF-8','GB2312')->removeHead()->query();
                $ql = QueryList::get($linkDatas[$p]['link'])->rules($rules_2)->encoding('UTF-8','GB2312')->removeHead()->query();

                $chapterDatas = $ql->getData();
                //print_r($chapterDatas);exit;

                if(!empty($chapterDatas))
                {
                    //----foreaech($chapterDates as $chapter){}
                    //查该小说的作者是否已经入库
                    $authorId = $this->checkAuthor($linkDatas[$p]['author']);
                    if(!empty($authorId))
                    {
                        //查该小说是否已经完结
                        $novelInfo = $this->getNovelData($linkDatas[$p]['novel'],$authorId);
                        //$novelFinishStatus==1，则小说已完结,跳出这个循环
                        if($novelInfo['finish']==1)
                        {
                            echo "finish=1";
                            //continue;
                        }
                        //相反则继续执行下去  0:未完结 3:数据库里没有这部小说
                        if($novelInfo['finish']==0)
                        {
                            echo "finish=0";
                            //先对比Chapter表中该小说最新的章节，没有匹对上的chapter采集
                            $lastChapterObj = Chapter::where('novel_id',$novelInfo['n_id'])->orderBy('chapter_order','desc')->first();
                            if($lastChapterObj)
                            {
                                echo 'no empty';
                                //echo $lastChapterArray->chapter_name;
                                $newChapterDataArray = array();
                                $k = 0;
                                for($j=count($chapterDatas)-1;$j>=0;$j--)
                                {
                                    if($chapterDatas[$j]['chaptername'] != $lastChapterObj->chapter_name)
                                    {
                                        if( isset($chapterDatas[$j]['chapterlink']))
                                        {
                                            $newChapterDataArray[$k]['chaptername'] = $chapterDatas[$j]['chaptername'];
                                            $newChapterDataArray[$k]['chapterlink'] = $chapterDatas[$j]['chapterlink'];
                                            $k++;
                                        }

                                    }else{
                                        break;
                                    }

                                }
                                if(empty($newChapterDataArray))
                                {
                                    //采集的数据跟数据里一样，没有更新，跳出这本小说的采集过程
                                    break;
                                }
                                $chapterDatas = array();
                                $k = 0;
                                for($c=count($newChapterDataArray)-1;$c>=0;$c--)
                                {
                                    $chapterDatas[$k]['chaptername'] = $newChapterDataArray[$c]['chaptername'];
                                    $chapterDatas[$k]['chapterlink'] = $newChapterDataArray[$c]['chapterlink'];
                                    $k++;
                                }

                                $chapterOrder = $lastChapterObj->chapter_order;
                                $chapterInsertId = $ChapterId = $lastChapterObj->chapter_id;
                                $novelInsertId = $lastChapterObj->novel_id;
                                $nextChapterId = $chapterInsertId;
                                $novelData = $novelInfo;

                            }else{
                                //chapter表里面找不到任何该小说的章节，则全部采集
                                echo "empty";
                                $chapterInsertId = 0;
                                $nextChapterId = 0;
                                $chapterOrder = 0;
                                $novelInsertId = $novelInfo['n_id'];
                                $novelData = $novelInfo;
                            }



                        }//if($novelFinishStatus['finish']==0)
                        if($novelInfo['finish']==3)
                        {
                            $replaceStr = array('[',']');
                            $categoryName = trim(str_replace($replaceStr,'',$linkDatas[$p]['category']));
                            $categoryId = $this->getCategoryIdByCategoryName($categoryName);

                            echo "finish=3";
                            //入库
                            //入novel数据库
                            $novelData['c_id'] = $categoryId;
                            $novelData['novel_name'] = $linkDatas[$p]['novel'];
                            $novelData['novel_name_pinyin'] = $this->translatePinyin($linkDatas[$p]['novel']);
                            $novelData['initial'] = substr( $novelData['novel_name_pinyin'], 0, 1 );
                            $novelData['postdate'] = time();
                            $novelData['lastupdate'] = time();
                            $novelData['author_id'] = $authorId;
                            $novelData['novel_intro'] = $chapterDatas[0]['intro'];

                            $reInsertNovel = Novel::create($novelData);
                            $novelInsertId = $reInsertNovel->n_id;


                            //判断该小说有没有封面,有的话就入库
                            if(!strstr($chapterDatas[0]['photo'], 'nocover'))
                            {
                                if( @fopen( $chapterDatas[0]['photo'], 'r' ) )
                                {
                                    $photoStatusData['novel_id'] = $novelInsertId;
                                    $photoStatusData['photo'] = $chapterDatas[0]['photo'];
                                    Photostatus::create($photoStatusData);
                                }
                            }

                            $chapterInsertId = 0;
                            $nextChapterId = 0;
                            $chapterOrder = 0;

                        }//if($novelFinishStatus==3)

                        $rules_3 = [
                            'content' => ['#BookText','html'],
                        ];
                        //采集chapter
                        for($i=0;$i<=3;$i++)
                        {
                            if($chapterDatas[$i]['chapterlink'] == '' or $chapterDatas[$i]['chaptername'] == '' or $chapterDatas[$i]['chaptername'] == '&nbsp;')
                            {
                                echo "abc";
                                continue;
                            }

                            $chapterContentData = QueryList::get($linkDatas[$p]['link'].$chapterDatas[$i]['chapterlink'])->rules($rules_3)->encoding('UTF-8','GB2312')->removeHead()->query()->getData();

                            $chapterData = array();
                            $chapterData['chapter_name'] = $chapterDatas[$i]['chaptername'];
                            $chapterData['chapter_order'] = $i+1+$chapterOrder;
                            $chapterData['pre_chapter_id'] = $chapterInsertId;
                            $chapterData['next_chapter_id'] = '0';
                            $chapterData['novel_id'] = $novelInsertId;
                            $chapterData['updatetime'] = time();
                            $chapterData['createtime'] = time();

                            $reInsertChapter = Chapter::create($chapterData);
                            $chapterInsertId = $reInsertChapter->chapter_id;


                            //入库后，该章节的内容写入txt文档
                            $chapherTxtDir = resource_path('novelchapter').DIRECTORY_SEPARATOR.date('Ymd',$novelData['postdate']).DIRECTORY_SEPARATOR.$novelInsertId.DIRECTORY_SEPARATOR;
                            $chapherTxtName = $chapterInsertId.".txt";
                            $this->writeNovelChapterIntoText($chapterContentData[0]['content'],$chapherTxtDir,$chapherTxtName);

                            if($i!=0 or $novelInfo['finish']==0)
                            {
                                //更新上一章节
                                $updateChapter['next_chapter_id'] = $chapterInsertId;
                                Chapter::where('chapter_id',$nextChapterId)->update($updateChapter);
                            }

                            $nextChapterId = $chapterInsertId;


                            if($i%50==0)
                            {
                                sleep($this->sleepTime);
                            }

                        }//for($i=0;$i<=count($chapterDatas);$i++)
                        //把该小说最后一章的信息更新到novel这个表
                        $lastChapterInfo = array();
                        $lastChapterInfo['lastchapter_id'] = $chapterInsertId;
                        $lastChapterInfo['lastchapter'] = $chapterData['chapter_name'];
                        //print_r($lastChapterInfo);
                        //echo "novelId:".$novelInsertId;
                        Novel::where('n_id',$novelInsertId)->update($lastChapterInfo);



                    }
                }

            }//for($p=0;$p<=count($linkDatas)-1;$p++)



        }//if(!empty($linkDatas))













    }

    //查看该小说作者是否已经在数据库里，没有的话就插入--返回authorId
    private function checkAuthor($authorName){
        $result = Author::select('author_id')->where('author',$authorName)->get()->toArray();
        if(!empty($result))
        {
            return $authorId = $result[0]['author_id'];
        }else{
            $data = array();
            $data['author'] = $authorName;
            $authorPinyin = $this->translatePinyin($authorName);
            $data['author_pinyin'] = $authorPinyin;
            $result = Author::create($data);
            return $result->author_id;
        }
    }

    //查看该小说是否已经完结了--找到数据:return array(), 找不到数据:return 3
    private function getNovelData($novelName,$authorId){
        $result = Novel::where('novel_name',$novelName)->where('author_id',$authorId)->get()->toArray();
        if(empty($result))
        {
            $reNovel['finish'] = 3;
            return $reNovel;
        }else{
            return $result[0];
        }
    }

    //中文转拼音
    private function translatePinyin($chinese){
        if(empty($chinese))
        {
            return null;
        }
        $pinyin = '';
        //引一个中文转换成拼音的类
        $pinyinClass = new Pinyin();
        $pinyinArray = $pinyinClass->convert($chinese);
        foreach ($pinyinArray as $pinyins)
        {
            $pinyin .= $pinyins;
        }
        return $pinyin;
    }

    //小说章节写入txt文本
    private function writeNovelChapterIntoText($chapterContent,$dir,$txtFileName){
        $this->createDir($dir);
        $myfile = fopen($dir.$txtFileName, "w");
        fwrite($myfile, $chapterContent);
        fclose($myfile);
        return true;

    }

    //创建文件夹
    private function createDir($path)
    {
        if (is_dir($path)) {
            return true;
        } else {
            $res = mkdir($path, 0777, true);
            return true;
        }

    }

    //通过小说类型名获取小说分类Id
    private function getCategoryIdByCategoryName($categoryName){
        $categoryId = 7;
        switch ($categoryName)
        {
            case '玄幻小说':
                $categoryId = 1;
                break;
            case '修真小说':
                $categoryId = 2;
                break;
            case '都市小说':
                $categoryId = 3;
                break;
            case '穿越小说':
                $categoryId = 4;
                break;
            case '网游小说':
                $categoryId = 5;
                break;
            case '科幻小说':
                $categoryId = 6;
                break;
            default:
                $categoryId = 7;

        }
        return $categoryId;
    }











}
