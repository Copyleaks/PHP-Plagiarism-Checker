<?php
namespace Copyleaks;


class ResultRecord{
	public function __construct($result){
		$this->URL = $result['URL'];
		$this->Percents = $result['Percents'];
		$this->NumberOfCopiedWords = $result['NumberOfCopiedWords'];
		$this->ComparisonReport = $result['ComparisonReport'];
        $this->CachedVersion = $result['CachedVersion'];
        $this->Title = $result['Title'];
		$this->Introduction = $result['Introduction'];
		$this->EmbededComparison = $result['EmbededComparison'];
	}
    
    public function __toString(){    
        return '<BR/>-----------------------------------------------' .
                '<BR/><strong>URL:</strong> ' . $this->URL .
                '<BR/><strong>Title:</strong> ' . $this->Title .
                '<BR/><strong>Introduction:</strong> ' . $this->Introduction .
                '<BR/><strong>Percents:</strong> ' . $this->Percents .
                '<BR/><strong>NumberOfCopiedWords:</strong> ' . $this->NumberOfCopiedWords .
                '<BR/><strong>ComparisonReport:</strong> ' . $this->ComparisonReport .
                '<BR/><strong>CachedVersion:</strong> ' . $this->CachedVersion .
                '<BR/><strong>EmbededComparison:</strong> ' . $this->EmbededComparison;
    }
}
?>