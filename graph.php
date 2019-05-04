<?php

$initialFunc = "1/(1 + (7.5e-7)*(x-4122.44898)**2) + (0.7)/(1 + (7.5e-7)*(x-4122.44898-(850))**2)";

function funcion($x, $funcion_String) {

    $funcion = str_replace('x', '($x)', $funcion_String);
    $funcion = str_replace('^', '**', $funcion);
    $funcion = preg_replace('/(\d)(\()/i', "\\1*\\2", $funcion);
    eval("\$funcion=" . $funcion . ";");
    return $funcion;
}

$a = 0;
$b = 8000;
$ecuacion = $_GET['funcion'];
$tipo = $_GET['tipo'];

if ($tipo == 1) {
    graph($ecuacion, $a, $b);
}

function valoresX() {

    for ($i = 0, $val = 1400; $i < 26; $i++, $val += 200) {
        $x[$i][0] = $val;
    }

    return $x;
}

/* function valoresYMedidos() {
  //datos medidos
  $y_s[0][0] = 0;
  $y_s[1][0] = 0;
  $y_s[2][0] = 0;
  $y_s[3][0] = 0;
  $y_s[4][0] = 0;
  $y_s[5][0] = 0;
  $y_s[6][0] = 0;
  $y_s[7][0] = 0;
  $y_s[8][0] = 0;
  $y_s[9][0] = 0;
  $y_s[10][0] = 0;
  $y_s[11][0] = 0;
  $y_s[12][0] = 2.068965517;
  $y_s[13][0] = 20.68965517;
  $y_s[14][0] = 41.37931034;
  $y_s[15][0] = 82.75862069;
  $y_s[16][0] = 134.4827586;
  $y_s[17][0] = 196.5517241;
  $y_s[18][0] = 289.6551724;
  $y_s[19][0] = 393.1034483;
  $y_s[20][0] = 517.2413793;
  $y_s[21][0] = 651.7241379;
  $y_s[22][0] = 786.2068966;
  $y_s[23][0] = 879.3103448;
  $y_s[24][0] = 982.7586207;
  $y_s[25][0] = 1055.172414;
  $y_s[26][0] = 1106.896552;
  $y_s[27][0] = 1106.896552;
  $y_s[28][0] = 1075.862069;
  $y_s[29][0] = 982.7586207;
  $y_s[30][0] = 889.6551724;
  $y_s[31][0] = 744.8275862;
  $y_s[32][0] = 620.6896552;
  $y_s[33][0] = 455.1724138;
  $y_s[34][0] = 320.6896552;
  $y_s[35][0] = 206.8965517;
  $y_s[36][0] = 134.4827586;
  $y_s[37][0] = 72.4137931;
  $y_s[38][0] = 22.75862069;
  $y_s[39][0] = 2.068965517;
  $y_s[40][0] = 0;
  $y_s[41][0] = 0;
  $y_s[42][0] = 0;
  $y_s[43][0] = 0;
  $y_s[44][0] = 0;
  $y_s[45][0] = 0;
  $y_s[46][0] = 0;
  $y_s[47][0] = 0;
  $y_s[48][0] = 0;
  $y_s[49][0] = 0;
  $y_s[50][0] = 0;

  return $y_s;
  } */

function valoresYMedidos() {
    $y_s[0][0] = 16.4119066773934;
    $y_s[1][0] = 42.4778761;
    $y_s[2][0] = 82.0595334;
    $y_s[3][0] = 125.502816;
    $y_s[4][0] = 197.908286;
    $y_s[5][0] = 304.1029767;
    $y_s[6][0] = 419.9517297;
    $y_s[7][0] = 516.4923572;
    $y_s[8][0] = 641.995173;
    $y_s[9][0] = 772.3250201;
    $y_s[10][0] = 891.069992;
    $y_s[11][0] = 977.9565567;
    $y_s[12][0] = 1055.189059;
    $y_s[13][0] = 1108.286404;
    $y_s[14][0] = 1110.217216;
    $y_s[15][0] = 1066.773934;
    $y_s[16][0] = 999.1954948;
    $y_s[17][0] = 904.5856798;
    $y_s[18][0] = 755.9131134;
    $y_s[19][0] = 611.1021722;
    $y_s[20][0] = 434.7723250;
    $y_s[21][0] = 299.2759453;
    $y_s[22][0] = 212.3893805;
    $y_s[23][0] = 142.8801287;
    $y_s[24][0] = 80.12872084;
    $y_s[25][0] = 28.96218825;
    return $y_s;
}

function yMax() {
    return 1117.241379;
}

function yMedidos($i) {
    $y = valoresYMedidos();
    return $y[$i][0];
}

//funcion que retorna el valor normalizado de Y, usado en los calculos
function yNormalizado($i) {
    return yMedidos($i) / yMax();
}

function vectorYNormalizado() {
    for ($i = 0; $i < 26; $i++) {
        $vector[$i][0] = yNormalizado($i);
    }
    return $vector;
}

function graph($funcion, $a, $b) {
    require_once '../phplot/phplot.php';
    global $initialFunc;
    $x = valoresX();
    $y = vectorYNormalizado();
    $data = array();
    for ($i = 0; $i < 26; $i ++) {
        $data[] = array('', $x[$i][0], $y[$i][0]);
    }
    $plot = new PHPlot_truecolor(1280, 720); #el tama?o del gr?fico, hay que ajustarlo seg?n el dise?o
    $plot->SetImageBorderType('plain');
    $plot->SetPrintImage(false);
    $plot->SetLineWidths(1);
    $plot->SetPlotType('linepoints');
    $plot->SetDataType('data-data');
    $plot->SetDataValues($data);
    $plot->SetDataColors('blue');
    $plot->SetPlotAreaWorld(-100, 0, 8100, 1.7);
    $plot->SetXDataLabelPos('none');
    $plot->SetPrecisionX(1);
    $plot->SetYTickIncrement(0.2);
    $plot->SetYLabelType('data');
    $plot->SetPrecisionY(1);
    $plot->SetDrawYGrid(True);
    $plot->DrawGraph();

    $delta = 0.1; #mientras mas peque?o sea esto, mas Ultra HD 4K va a salir el gr?fico
    $data1 = array();
    for ($x = $a; $x <= $b; $x += $delta) {
        if (!is_nan(funcion($x, $funcion))) {
            $data1[] = array('', $x, funcion($x, $funcion),funcion($x, $initialFunc));
        }
    }

    $plot->SetImageBorderType('plain');
    $plot->SetPlotType('lines');
    $plot->SetDataType('data-data');
    $plot->SetDataValues($data1);
    $plot->SetDataColors(array('red','green'));
    $plot->SetLegend(array($funcion,$initialFunc));
    $plot->DrawGraph();

    //$plot->SetDrawXGrid(True);
    $plot->SetDrawYGrid(True);
    //$plot->SetCallback('drawsetup', 'pre_plot');
    $plot->DrawGraph();
    $plot->PrintImage();
}

?>
