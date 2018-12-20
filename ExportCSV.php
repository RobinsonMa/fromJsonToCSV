<?php


/**
 * Class ExportCSV
 */
class ExportCSV{


    /**
     * 需要生成csv的json文件，文件规则多个文件按顺序，第一个为0 0response.json 1response.json
     * @var string
     */
    public $path = './input/';

    /**
     * 生成的csv文件
     * @var string
     */
    public $outputFile = './output/response.csv';

    /**
     *
     */
    public function run(){
        $dir = scandir($this->path,0);
        foreach ($dir as $value){
            if (strpos($value,'response.json')!==false){
                $inputFile = $this->path . $value;
                if ($inputFile === $this->path . '0response.json'){
                    $this->export($inputFile,true);
                }else{
                    $this->export($inputFile);
                }
            }
        }
    }

    /**
     * @param $inputFile
     * @param bool $isFirstFile
     * @return bool
     */
    public function export($inputFile,$isFirstFile=false){
        $aJson = file_get_contents($inputFile);
        $aArr = json_decode($aJson,true);
        $aArr = $aArr['body']['content'];
        //第一个导入的文件需要添加文件头
        if ($isFirstFile){
            $title = '';
            foreach ($aArr['columns'] as $key=>$value){
                $value = str_replace([',',"\n"],['_douhao_','_huanhuang_'],$value);
                $title .= $value . ',';
            }
            $title = rtrim($title,',') . "\n";
            file_put_contents($this->outputFile,$title);
        }


        foreach ($aArr['items'] as $key=>$value){
            $value = str_replace([',',"\n"],['_douhao_','_huanhuang_'],$value);
            $row = '';
            foreach ($value as $key2=>$value2){
                $row .= $value2 . ',';
            }
            $row = rtrim($row,',') . "\n";
            file_put_contents($this->outputFile,$row,8);
            echo $key . "\n";
        }
        return true;
    }
}



$ExportCSV = new ExportCSV();
$ExportCSV->run();