<?php

namespace App\Widgets;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class LineGraph
{
    const TURQUOISE = "#1abc9c";
    const EMERALD = "#2ecc71";
    const PETER_RIVER = "#3498db";
    const AMETHYST = "#9b59b6";
    const WET_ASPHALT = "#34495e";
    const GREEN_SEA = "#16a085";
    const NEPHRITIS = "#27ae60";
    const BELIZE_HOLE = "#2980b9";
    const WISTERIA = "#8e44ad";
    const MIDNIGHT_BLUE = "#2c3e50";
    const SUN_FLOWER = "#f1c40f";
    const CARROT = "#e67e22";
    const ALIZARIN = "#e74c3c";
    const CLOUDS = "#ecf0f1";
    const CONCRETE = "#95a5a6";
    const ORANGE = "#f39c12";
    const PUMPKIN = "#d35400";
    const POMEGRANATE = "#c0392b";
    const SILVER = "#bdc3c7";
    const ASBESTOS = "#7f8c8d ";

    private $graphTitle = "";
    private $querys;
    private $titles = [];
    private $colors = [];
    private $x = [];
    private $width = 12;

    /**
     * LineGraph constructor.
     * @param array $querys
     */
    public function __construct($title="")
    {
        $this->graphTitle = $title;
    }

    public function setLine($title,$query,$color)
    {
        $this->title = $title;
        $this->titles[] = $title;
        $this->querys[] = $query;

        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $this->colors[] = [
            'r'=>$r,
            'g'=>$g,
            'b'=>$b,
        ];
    }

    public function setXLabels($values)
    {
        $this->x = $values;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }



    public function run()
    {
        $id = rand();
        $width = $this->width;

        $values = $this->querys;
        $titles = $this->titles;
        $graphTitle = $this->graphTitle;

        $title = $this->title;
        $colors = $this->colors;
        $x = $this->x;
        return view('widgets.lineGraph',compact('id','width','values','titles','graphTitle','title','colors','x'));
    }

}
