<?php

namespace App\Http\Controllers;

use App\Http\Controllers\widgets\AuctionProductsController;
use App\Widgets\AveragePrices;
use App\Widgets\BuyResumen;
use App\Widgets\LineGraph;
use App\Widgets\NextsArrives;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Boat;
use Auth;
use App\Widgets\AuctionProducts;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $widget = [];
		if (Auth::user()->type == \App\User::VENDEDOR){

            //Grafico Ganancias
            $ventas = Auth::user()->seller->getMyMonthSales();
            $graphGanancias = new LineGraph('Grafico Ganancias Mensuales');
            $labels = [];
            foreach ($ventas as $k=>$v) {
                $labels[] = date("F",mktime(0,0,0,$k,1,2011));
            }
            $graphGanancias->setXLabels($labels);
            $graphGanancias->setLine('Ganancias', array_values ($ventas) , LineGraph::EMERALD );
            $widgets['graphGanancias'] = $graphGanancias->run();

            //Grafico Arrivos
            $arrivos = Auth::user()->seller->getMyMonthArrives();
            $graphArribos = new LineGraph('Grafico Arribos Mensuales');
            $graphArribos->setXLabels($labels);
            $graphArribos->setLine('Arrivos', array_values ($arrivos) , LineGraph::PETER_RIVER );
            $widgets['graphArrivos'] = $graphArribos->run();


        }
        if (Auth::user()->type == \App\User::COMPRADOR){
            $widget = [
                AuctionProducts::class,
                NextsArrives::class,
                BuyResumen::class,
                AveragePrices::class,
            ];
        }

        foreach ($widget as $w) {
            $oWidget = new $w;
            $widgets[(new \ReflectionClass($oWidget))->getShortName()] = $oWidget::run();
        }

        $approved = Auth::user()->isApproved();
		return view('home.index', compact('widgets', 'approved'));
    }

}
