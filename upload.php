<?php


class DataAnalytics
{

    private $_isUploaded = false,
            $_file,
            $_fileTypes = ["text/csv", "application/vnd.ms-excel"],
            $_data,
            $_results = array(),
            $_highestAverage = 0,
            $_division_r = 30,
            $_age_r = 30,
            $_timezone_r = 40;


    public function uploadFile( $file )
    {
       
        $this->_file = $file;
        if( $this->validateFile() )
        {
            $this->_isUploaded = true;
            $this->setData();
            $this->_division_r = intval($_POST['division_r']) !== 0 ? intval($_POST['division_r']) : $this->_division_r;
            $this->_age_r = intval($_POST['age_r']) !== 0 ? intval($_POST['age_r']) : $this->_age_r;
            $this->_timezone_r = intval($_POST['timezone_r']) !== 0 ? intval($_POST['timezone_r']) : $this->_timezone_r;
            return true;
        }
    }

    public function setData()
    {
        $this->_data = File($this->_file['tmp_name']);
    }

    public function validateFile()
    {
        if( in_array( $this->_file['type'], $this->_fileTypes ) )
        {
            return true;
        }

        return false;

    }   

    public function isUploaded()
    {
        return $this->_isUploaded;
    }

    public function dataAnalyze()
    {
        $this->setStartData();

        for( $i = 0; $i < count($this->_results); $i++ )
        {
            for( $a = ( $i + 1 ); $a < count($this->_results); $a++  )
            {
                $this->compare( $i, $a );

            }
        }
    }

    private function setStartData()
    {
        for( $i = 1; $i < count($this->_data); $i++ )
        {
            $e = explode(",", $this->_data[$i]);
            $p = [
                'name' => $e[0],
                'email' => $e[1],
                'division' => $e[2],
                'age' => $e[3],
                'timezone' => $e[4],
                'compares' => array()
            ];
            array_push($this->_results, $p);
        }

        
    }

    private function compare( $a, $b )
    {
        $score = 0;

        if(strtolower($this->_results[$a]['division']) === strtolower($this->_results[$b]['division']))
        {
            $score += $this->_division_r;
        }

        if( abs( $this->_results[$a]['age'] - $this->_results[$b]['age'] ) <= 5 )
        {
            $score += $this->_age_r;
        }

        if( $this->_results[$a]['timezone'] === $this->_results[$b]['timezone'] )
        {
            $score += $this->_timezone_r;
        }

        $a_results = [
            'name' => $this->_results[$a]['name'],
            'score' => $score
        ];

        $b_results = [
            'name' => $this->_results[$b]['name'],
            'score' => $score
        ];

        array_push($this->_results[$a]['compares'], $b_results);
        array_push($this->_results[$b]['compares'], $a_results);

        $this->_highestAverage += $score;
    }


    public function getHighestAverage()
    {
        return $this->_highestAverage / count($this->_results);
    }


    public function getResults()
    {
        return $this->_results;
    }

}
?>