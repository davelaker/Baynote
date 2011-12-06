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
    
    public function find2HighestNumber($arrayOfNumbers, $type='loop') {
        
        if(!is_array($arrayOfNumbers)) {
            # do some error logging if required
            throw new Exception('The first parameter must be an array');
        }
        
        foreach($arrayOfNumbers as $number) {
            if(!is_numeric($number)) {
                throw new Exception('All elements in the array must have a numeric value');
            }
        }
        
        switch($type) {
            case 'heap' :
                $twoHighest = $this->_heap($arrayOfNumbers);
                break;
            case 'sorting' :
                $twoHighest = $this->_sorting($arrayOfNumbers);
                break;
            default :
            case 'loop' :
                $twoHighest = $this->_loop($arrayOfNumbers);
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
    private function _heap($arrayOfNumbers) {
        $maxHeap = new BaynoteHeap();
       
        array_map(array($maxHeap, 'insert'), $arrayOfNumbers);
        # note array_map is approxiamntely 25% quicker than looping through array
        
        #foreach($arrayOfNumbers as $number) {
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
    private function _sorting($arrayOfNumbers) {
        asort($arrayOfNumbers);
        $sortedNumbers = array_slice($arrayOfNumbers, -2);
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
    private function _loop($arrayOfNumbers) {
        $highest = null;
        $secondHighest = null;
        foreach($arrayOfNumbers as $number) {
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
    $Baynote = new Baynote();

    # This is best way to calculate the 2 numbers with O(N)
    $time=microtime(true);
    $twoHighest = $Baynote->find2HighestNumber($arrayOfNumbers, 'loop');
    $time=microtime(true)-$time;
    echo 'Loop: '.$time."\n";
    var_dump($twoHighest);

    # I also wanted to tryout a couple of alternatives to compare speed
    $time=microtime(true);
    $twoHighest = $Baynote->find2HighestNumber($arrayOfNumbers, 'heap');
    $time=microtime(true)-$time;
    echo 'Heap: '.$time."\n";
    var_dump($twoHighest);

    $time=microtime(true);
    $twoHighest = $Baynote->find2HighestNumber($arrayOfNumbers, 'sorting');
    $time=microtime(true)-$time;
    echo 'Sorting: '.$time."\n";
    var_dump($twoHighest);
    
} catch (Exception $e) {
    #var_dump($e);
    # Handle Exception however you require / logging, output etc";
    echo $e->getMessage();
}