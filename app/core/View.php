<?php


namespace Homework\core;


class View
{
    private $fileName;
    private $html;
    private $generatedFile;
    private $vars;

    /**
     * View constructor.
     * @param $fileName
     * @param array $vars
     */
    public function __construct($fileName, $vars)
    {
        $this->fileName = $fileName;
        $this->vars = $vars;

        $this->html = file_get_contents(VIEW_PATH . $this->fileName .".php");
    }

    public static function generate(string $name, $vars = [])
    {
        $view = new static($name,$vars);

        $view->startEngine();

//        $view->getHeader();
        echo $view->setView($view->fileName);
//        $view->getFooter();

    }

    private function generateForeach()
    {
        preg_match("/@foreach(.*)/",$this->html,$part);

        if(count($part) != 0)
        {
            $this->html = preg_replace_callback("/@foreach(.*)/",function($target){
                return "<?php foreach". rtrim($target[1]) ." : ?>";
            },$this->html);

            $this->html = preg_replace_callback("/@endforeach/",function($target){
                return "<?php endforeach; ?>";
            },$this->html);

        }

    }

    private function generateEcho()
    {
        preg_match_all("/{{(.*)}}/",$this->html,$parts);

        if(count($parts) != 0)
        {
            $this->html = preg_replace_callback("/{{(.*)}}/",function($echo){
               return "<?= htmlspecialchars(". strip_tags($echo[1]) .",ENT_QUOTES, 'UTF-8') ?>";
            },$this->html);

        }
    }

    private function generateIsset()
    {
        preg_match("/@isset(.*)/",$this->html,$part);

        if(count($part) != 0)
        {
            $this->html = preg_replace_callback("/@isset(.*)/",function($target){
                return "<?php if (isset". rtrim($target[1]) ."): ?>";
            },$this->html);

            $this->html = preg_replace_callback("/@endisset/",function($target){
                return "<?php endif; ?>";
            },$this->html);
        }

    }


    private function generateInclude()
    {
        preg_match("/@include(\(.*)\)/",$this->html,$part);
        if(count($part) != 0)
        {
            $this->html = preg_replace_callback("/@include(\(.*)\)/",function($target){
                $args = explode(",",ltrim(rtrim($target[1]),"("));

                if(isset($args[1]))
                {
                    eval('$param = ' . $args[1] . ";");

                    $this->vars = $this->vars + $param;
                }

                $parameters = [];
                if(isset($param)){
                    foreach ($param as $key => $p){
                        $parameters[] = "$" . $key;
                    }
                }

                return str_replace('$param',implode(",",$parameters),file_get_contents(VIEW_PATH . trim($args[0],'"') .".php"));
            },$this->html);

            $this->generateInclude();
        }
    }

    private function generateFor()
    {
        preg_match("/@endfor\b/",$this->html,$part);
        if(count($part) != 0)
        {
            $this->html = preg_replace_callback("/@for\((.*)/",function($target){
                return "<?php for(". rtrim($target[1]) .": ?>";
            },$this->html);

            $this->html = preg_replace_callback("/@endfor\b/",function($target){
                return "<?php endfor; ?>";
            },$this->html);
        }
    }

    private function generateIf()
    {
        preg_match("/@if(.*)/",$this->html,$part);
        if(count($part) != 0)
        {
            $this->html = preg_replace_callback("/@if(.*)/",function($target){
                return "<?php if ". rtrim($target[1]) .": ?>";
            },$this->html);

            $this->html = preg_replace_callback("/@else/",function($target){
                return "<?php else : ?>";
            },$this->html);

            $this->html = preg_replace_callback("/@endif/",function($target){
                return "<?php endif; ?>";
            },$this->html);

        }
    }

    /**
     * Method to assemble page with extend
     */
    private function assemblePage():void
    {
        // Look for extend in file
        preg_match("/@extend(.*)/",$this->html,$part);

        // If extend is present in file run code
        if(count($part) != 0){
            // Strip all extra brackets and quotes from extend to get file name
            $file = trim(str_replace(['(',')','"'],"",$part[1]));

            // Get file contents of file and put in $this->html
            $this->html = preg_replace("/@extend(.*)/",file_get_contents(VIEW_PATH . "$file.php"),$this->html);

            // Look for all sections within original file
            preg_match_all("/(?=\@section).*?(?=\@endsection)/s",$this->html,$parts);

            // Create empty array for all sections found
            $sections = [];

            // loop over sections found
            foreach ($parts[0] as $part)
            {
                // Look for @section within content
                preg_match("/@section(.*)/",$part,$sect);
                // Strip all extra brackets and quotes to get section name
                $section = trim(str_replace(['(',')','"'],"",$sect[1]));
                // Fill array with section name as key and section content as value
                $sections[$section] = str_replace($sect[0],"",$part);
            }

            // Find and replace all sections and content with a empty string
            $this->html = preg_replace_callback("/@section.*?@endsection/s",function ($section){
                return "";
            },$this->html);

            // Loop over all sections and replace matching @yield string within $this->html
            foreach ($sections as $key => $newSection){
                $this->html = str_replace(["@yield('". $key."')", '@yield("'. $key .'")'], trim($newSection), $this->html);
            }
        }

        // After all Yields have been replaced look for remaining yields and replace them with empty strings
        $this->html = preg_replace_callback("/@yield(.*)\)/", function ($yield){

            // Strip all extra brackets and quotes to get yield parameters
            $check = str_replace(["(",")","'",'"'],"",$yield[1]);
            // Explode parameters to find a default value for the yield
            $param_check = explode(",",$check);

            if($check == "title")
            {
                return PROJECT_NAME;
            }

            // If default value is found replace yield with default else return a empty string
            if(count($param_check) > 1){
                return $param_check[1];
            }
            return "";

        },$this->html);
    }




    public function startEngine()
    {
        $this->assemblePage();

        $fileName = explode("/",$this->fileName);
        $fileName = $fileName[count($fileName) - 1];

        $this->generatedFile = VIEW_PATH . "temp/slash-$fileName.php";

        $this->generateInclude();

        $this->generateEcho();
        $this->generateFor();
        $this->generateForeach();
        $this->generateIf();
        $this->generateIsset();

        $this->createAndWriteToFile();

        ob_start();

        extract($this->vars);

        include $this->generatedFile;

        $this->html = ob_get_clean();


        $this->createAndWriteToFile();
        $this->fileName = $this->generatedFile;

    }

    private function createAndWriteToFile()
    {
//        var_dump($this->html);
        $newFile = fopen($this->generatedFile,'w');
        fwrite($newFile,$this->html);
        fclose($newFile);
    }



    // Use other method instead of include
    private function setView($file)
    {

        return file_get_contents($file);
    }

    private function getHeader()
    {
        return include DATA_PATH . "../views/header.php";
    }

    private function getFooter()
    {
        return include DATA_PATH . "../views/footer.php";
    }

}