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
    public $dateFormat = 'F';
    
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
        
        
        return $this->createCalendar($tableBegin, $tableEnd);
    }
    
    public function createCalendar($tableBegin, $tableEnd)
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
        
        $prevRoute = $this->monthRoute;
        $prevRoute['month'] = $month == 1? 1 : $month-1;
        $previous = Html::a(Yii::t('app', '<span class="pull-left">&laquo;</span>'), Url::to($prevRoute));
        
        $nextRoute = $this->monthRoute;
        $nextRoute['month'] = $month == 12? 12 : $month+1;
        $next = Html::a(Yii::t('app', '<span class="pull-right">&raquo;</span>'), Url::to($nextRoute));


        $result = '<div><h3 style="text-align:center;">'.$previous.' '.$title.' '.$next.'</h3>'.$tableBegin;
        //get total days in month
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);  
        for($i=1; $i<=$days; $i++)
        {
            $result = $result.'<tr>';
            for($j=0; $j<$rowSize & $i<=$days; $j++)
            {
                $actionRoute = $this->actionRoute;
                $actionRoute['day'] = $i;
                $actionRoute['month'] = $month;
                $actionRoute['year'] = $year; 
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
}
