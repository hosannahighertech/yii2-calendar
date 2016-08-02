<?php

namespace hosannahighertech\widgets;

/**
 * Simple calendar
 * @author Stefano D. Mtangoo
 * @copyright Hosanna Higher Technologies
 * @licence MIT
 */
 
class CalendarSimple extends \yii\base\Widget
{
    public $controller = 'default';
    public $action = 'index';
    
    public $currentDate = 'index';
    public $dateFormat = 'd-M-Y';
    
    public function run()
    {
        return '
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <table class="table table-bordered table-condensed">
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
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="alert alert-danger">2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>9</td>
                                <td>10</td>
                                <td>11</td>
                                <td>12</td>
                                <td>13</td>
                                <td>14</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>15</td>
                                <td>17</td>
                                <td>18</td>
                                <td>19</td>
                                <td>20</td>
                                <td>21</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>23</td>
                                <td>24</td>
                                <td>25</td>
                                <td>26</td>
                                <td>27</td>
                                <td>28</td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>30</td>
                                <td>31</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>';
    }
}
