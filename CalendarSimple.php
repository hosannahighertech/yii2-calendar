<?php

namespace hosannahighertech\widgets;

/**
 * Simple calendar
 * @author Stefano D. Mtangoo
 * @copyright Hosanna Higher Technologies
 * @licence MIT
 */
 
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;
 
class CalendarSimple extends \yii\base\Widget
{
    public $monthRoute = ['default/index']; 
    public $actionRoute = ['action/index']; 
    
    public $currentDate = null;
    public $dateFormat = 'F Y';
    public $type = 'F Y';
    
    public function init()
    {
        if(is_null($this->currentDate))
            $this->currentDate = time();
            
        return parent::init();
    }
    
    public function run()
    {
        $tableBegin = '
            <table class="table table-bordered table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                    </tr>
                </thead>
                <tbody>'; 
                
        $tableEnd = '</tbody>
                <tbody></tbody>
            </table>';
        
        if($this->type=='weekly')
            return $this->createWeeklyCalendar();
        else
            return $this->createSimpleCalendar($tableBegin, $tableEnd);
    }
    
    public function createSimpleCalendar($tableBegin, $tableEnd)
    {
        $title = ''; 
        $time = $this->currentDate; 
        $rowSize = 7;
        
        if(is_numeric($this->currentDate)) 
            $title = date($this->dateFormat, $this->currentDate); 
        else 
        {
            $title = date($this->dateFormat, strtotime($this->currentDate)); 
            $time = strtotime($this->currentDate); 
        }
        
        $month = intval(date('m', $time));
        $year = intval(date('Y', $time));
        
        $timeOfFirstDay = strtotime($year.'-'.$month.'-1');      
        $dayOfMonth = date('N',$timeOfFirstDay);
        
        $prevRoute = $this->monthRoute;
        $prevRoute['month'] = $month == 1? 1 : $month-1;
        $previous = Html::a(Yii::t('app', '<span class="pull-left">&laquo;</span>'), Url::to($prevRoute));
        
        $nextRoute = $this->monthRoute;
        $nextRoute['month'] = $month == 12? 12 : $month+1;
        $next = Html::a(Yii::t('app', '<span class="pull-right">&raquo;</span>'), Url::to($nextRoute));


        $result = '<div><h3 style="text-align:center;">'.$previous.' '.$title.' '.$next.'</h3>'.$tableBegin;
        //get total days in month
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);  
        //add offset of when day one is supposed to be (its not always on monday)
        //$days+=$dayOfMonth
        for($i=1; $i<=$days; $i++)
        {
            $result = $result.'<tr>';
            for($j=0; $j<$rowSize & $i<=$days; $j++)
            {
                $actionRoute = $this->actionRoute;
                $actionRoute['day'] = $i;
                $actionRoute['month'] = $month;
                $actionRoute['year'] = $year; 
                
                if($dayOfMonth>1)
                {
                    $multiplied = str_repeat("<td> </td>", $dayOfMonth-1); 
                    $result = $result.$multiplied;
                    $j = $j+$dayOfMonth-1;
                    //reset to 1 so that this is done once only
                    $dayOfMonth = 1;
                }
                
                if($i==intval(date('d')))
                {
                    $dayLink = Html::a(Yii::t('app', $i), Url::to($actionRoute), ['class'=>'text-warning']);                
                    $result = $result.'<td style="text-align:center;" class="bg-danger">'.$dayLink.'</td>';
                }
                else
                {
                    $dayLink = Html::a(Yii::t('app', $i, ['style'=>'text-align:center;']), Url::to($actionRoute));                
                    $result = $result.'<td style="text-align:center;">'.$dayLink.'</td>';
                }
                
                $i++;
            }  
            $i--; //reset it as it skips one number here
            $result = $result.'</tr>';
        }
        $result = $result.$tableEnd.'</div>';
        return $result;
    }
    
    
    public function createWeeklyCalendar()
    {
        $title = ''; 
        $time = $this->currentDate;  
        
        if(!is_numeric($this->currentDate))  
            $time = strtotime($this->currentDate);  
        
        $month = intval(date('m', $time));
        $year = intval(date('Y', $time)); 
        
        $year = date('Y');
        //$week_count = date('W', strtotime($year . '-12-31')); //get all weeks in years
        $week_count = date('W', time()); 

        $result = '<table class="table table-bordered table-condensed table-responsive">';  
        for($i=1; $i<=$week_count; $i++)
        {
            $row = '<tr>';
            for($col=0; $col<6 & $i<=$week_count; $col++)
            { 
                $row = $row.'<td style="text-align:center;">';  
                
                $startEndDates = $this->getStartAndEndDate($i, $year); 
                $title = Yii::t('app', '<h4>Week {w} </h4>{s} - {e}', ['w'=>$i, 's'=>$startEndDates[0], 'e'=>$startEndDates[1]]);
                
                $actionRoute = $this->actionRoute;
                $actionRoute['week'] = $i; 
                $actionRoute['year'] = $year;  
                $dayLink = Html::a(Yii::t('app', $title, ['style'=>'text-align:center;']), Url::to($actionRoute));            
                $row = $row.$dayLink;  
                
                $row .='</td>'; 
                $i++;
            }
            $i--;
            $row .='</tr>'; 
            $result .= $row;
        }
        $result = $result.'</table></div>';
        return $result;
    }

    function getStartAndEndDate($week, $year){
        $dates[0] = date("M d", strtotime($year.'W'.str_pad($week, 2, 0, STR_PAD_LEFT)));
        $dates[1] = date("d", strtotime($year.'W'.str_pad($week, 2, 0, STR_PAD_LEFT).' +6 days'));
        return $dates;
    }
}
