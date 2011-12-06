<?php
/**
 * PHP 5.3 required for the SPLHeap
 */
class BaynoteHeap extends SplMaxHeap {
    public function compare($a, $b) {
        return $a - $b;
    }
}

class Baynote {
    
    private $arrayOfNumbers;
    
    public function __construct($arrayOfNumbers) {
        
        if(!is_array($arrayOfNumbers)) {
            # do some error logging if required
            throw new Exception('The first parameter must be an array');
        }
        
        foreach($arrayOfNumbers as $number) {
            if(!is_numeric($number)) {
                throw new Exception('All elements in the array must have a numeric value');
            }
        }
        
        $this->arrayOfNumbers = $arrayOfNumbers;
        
    }
    
    public function find2HighestNumber($type='loop') {
        
        
        
        switch($type) {
            case 'heap' :
                $twoHighest = $this->_heap();
                break;
            case 'sorting' :
                $twoHighest = $this->_sorting();
                break;
            default :
            case 'loop' :
                $twoHighest = $this->_loop();
                break;
            
        }
        
        return $twoHighest;
        
    }
    
    /**
     * Use the Heap Datastructure
     * 
     * @param array $arrayOfNumbers
     * @return array 
     */
    private function _heap() {
        $maxHeap = new BaynoteHeap();
       
        array_map(array($maxHeap, 'insert'), $this->arrayOfNumbers);
        # NOTE, my benchmark shows array_map is quicker than looping through array (foreach - below)
        
        #foreach($this->arrayOfNumbers as $number) {
        #    $maxHeap->insert($number);
        #}
        
        $highest = $maxHeap->extract();
        $secondHighest = $maxHeap->extract();
        return array($highest, $secondHighest);
    }
    
    /**
     * Sort the array then take last two elements
     * 
     * @param array $arrayOfNumbers
     * @return array 
     */
    private function _sorting() {
        asort($this->arrayOfNumbers);
        $sortedNumbers = array_slice($this->arrayOfNumbers, -2);
        $highest = $sortedNumbers[1];
        $secondHighest = $sortedNumbers[0];
        return array($highest, $secondHighest);
    }
    
    /**
     * Loop through each number seeing if it's bigger than biggest or 2nd bigest
     * 
     * @param array $arrayOfNumbers
     * @return array 
     */
    private function _loop() {
        $highest = null;
        $secondHighest = null;
        foreach($this->arrayOfNumbers as $number) {
            if($number > $highest) {
                $secondHighest = $highest;
                $highest = $number;
            } 
            elseif($number > $secondHighest) {
                $secondHighest = $number;
            }
        }
        return array($highest, $secondHighest);
    }
}

#$time=microtime(true);
$arrayOfNumbers = array();
for($i=1; $i<= 10000; ++$i) {
    $arrayOfNumbers[] = rand(1, 99999);
}
#$time=microtime(true)-$time;
#echo 'Array Generation: '.$time."<br /><br />";

try {
    $Baynote = new Baynote($arrayOfNumbers);

    # This is best way to calculate the 2 numbers with O(N)
    $time=microtime(true);
    $twoHighest = $Baynote->find2HighestNumber('loop');
    $time=microtime(true)-$time;
    echo 'Loop: '.$time."\n";
    var_dump($twoHighest);

    # I also wanted to tryout a couple of alternatives to compare speed
    $time=microtime(true);
    $twoHighest = $Baynote->find2HighestNumber('heap');
    $time=microtime(true)-$time;
    echo 'Heap: '.$time."\n";
    var_dump($twoHighest);

    $time=microtime(true);
    $twoHighest = $Baynote->find2HighestNumber('sorting');
    $time=microtime(true)-$time;
    echo 'Sorting: '.$time."\n";
    var_dump($twoHighest);
    
} catch (Exception $e) {
    #var_dump($e);
    # Handle Exception however you require / logging, output etc";
    echo $e->getMessage();
}