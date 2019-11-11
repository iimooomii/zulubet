<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2010
 */
Phar::mapPhar();
/**
 * Autoloader for the morph package
 *
 */
class MorphAutoloader
{
    /**
     * A static array of classes
     *
     * @var array
     */
    private static $classes = array(
        'morph\\Collection'                  => 'phar://Morph/Collection.php',
        'morph\\Enum'                        => 'phar://Morph/Enum.php',
        'morph\\Utils'                       => 'phar://Morph/Utils.php',
        'morph\\Iterator'                    => 'phar://Morph/Iterator.php',
        'morph\\compare\\NumericProperty'    => 'phar://Morph/compare/NumericProperty.php',
        'morph\\compare\\Property'           => 'phar://Morph/compare/Property.php',
        'morph\\PropertySet'                 => 'phar://Morph/PropertySet.php',
        'morph\\Query'                       => 'phar://Morph/Query.php',
        'morph\\IQuery'                      => 'phar://Morph/IQuery.php',
        'morph\\Object'                      => 'phar://Morph/Object.php',
        'morph\\ICompare'                    => 'phar://Morph/ICompare.php',
        'morph\\Storage'                     => 'phar://Morph/Storage.php',
        'morph\\property\\HasMany'           => 'phar://Morph/property/HasMany.php',
        'morph\\property\\Date'              => 'phar://Morph/property/Date.php',
        'morph\\property\\ComposeMany'       => 'phar://Morph/property/ComposeMany.php',
        'morph\\property\\HasOne'            => 'phar://Morph/property/HasOne.php',
        'morph\\property\\Enum'              => 'phar://Morph/property/Enum.php',
        'morph\\property\\Integer'           => 'phar://Morph/property/Integer.php',
        'morph\\property\\File'              => 'phar://Morph/property/File.php',
        'morph\\property\\Float'             => 'phar://Morph/property/Float.php',
        'morph\\property\\ComposeOne'        => 'phar://Morph/property/ComposeOne.php',
        'morph\\property\\String'            => 'phar://Morph/property/String.php',
        'morph\\property\\Generic'           => 'phar://Morph/property/Generic.php',
        'morph\\property\\Boolean'           => 'phar://Morph/property/Boolean.php',
        'morph\\property\\BinaryData'        => 'phar://Morph/property/BinaryData.php',
        'morph\\property\\Integer32'         => 'phar://Morph/property/Integer32.php',
        'morph\\property\\Integer64'         => 'phar://Morph/property/Integer64.php',
        'morph\\property\\Regex'             => 'phar://Morph/property/Regex.php',
    	'morph\\property\\Complex'           => 'phar://Morph/property/Complex.php',
    	'morph\\property\\StatefulCollection'=> 'phar://Morph/property/StatefulCollection.php',
        'morph\\query\\Property'             => 'phar://Morph/query/Property.php',
        'morph\\format\\Collection'          => 'phar://Morph/format/Collection.php',
        'morph\\exception\\ObjectNotFound'   => 'phar://Morph/exception/ObjectNotFound.php',
    );
    /**
     * class loader
     *
     * @param string $className
     * @return boolean
     */
    public static function load($className)
    {
        $isLoaded = false;
        if (isset(self::$classes[$className])) {
            include self::$classes[$className];
            $isLoaded = true;
        }
        return $isLoaded;
    }
}
//register the autoloader
spl_autoload_register(array('MorphAutoloader', 'load'));
__HALT_COMPILER();
require 'scraperwiki.php';
# Blank PHP
require 'scraperwiki/simple_html_dom.php';           
$sportday = new DateTime();
$sportday->sub(new DateInterval('P1D'));
    $url = "http://www.zulubet.com/".$sportday->format("Y-m-d")."/";
    $html = scraperWiki::scrape($url);        
    $dom = new simple_html_dom();
    $dom->load($html);
    foreach($dom->find("content_table") as $data){
        $home="";
        $score="";
        $away="";
        $odds1 = "";
        $odds2 = "";
        $odds3 = "";
        $pred1="";
        $pred2="";
        $pred3="";
        $prediction="";
        $record = $data->find("td.home a");    
        if($record!=null){
            $home = $record[0]->innertext;            
        }
        $record = $data->find("td.score a");    
        if($record!=null){
            $score = $record[0]->innertext;            
        }
        $record = $data->find("td.away a");    
        if($record!=null){
            $away = $record[0]->innertext;            
        }    
        $record = $data->find("td.odds");    
        if($record!=null){
            $odds1 = $record[0]->innertext;    
            $odds2 = $record[1]->innertext;    
            $odds3 = $record[2]->innertext;            
        }
        $record = $data->find("td.prob table.prob");
        if($record!=null){
            $prediction = $record[0]->title;        
            $preds = preg_split("/[\s()]+/", $prediction);
            $pred1=$preds[1];
            $pred2=$preds[3];
            $pred3=$preds[5];
        }
        if($home!=""){
            $row = array(
                "date"=>$sportday,
                "home"=>$home,
                "away"=>$away,
                "score"=>$score,
                "hodds"=>$odds1,
                "dodds"=>$odds2,
                "aodds"=>$odds3,
                "hpred"=>$pred1,
                "dpred"=>$pred2,
                "spred"=>$pred3
            );            
            scraperwiki::save(array(), $row);
        }
    }
?>
