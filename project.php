<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <script src="https://unpkg.com/d3@3/d3.min.js"></script>
    <script src="https://unpkg.com/function-plot@1/dist/function-plot.js"></script>
    <head>
        <meta charset="UTF-8">
        <title>Numeric Algorithms</title>
    <h3 style="text-align: center;font-family: arial;">Numeric Algorithms</h3>
    <h3 style="text-align: center;font-family: arial;">Adjustment of curves using cushioned least squares</h3>
    <br>
    <br>
    <div align = "center"><fieldset align = "center" style = "display:inline-block;width: 1500px;"><legend>Data</legend>
            <form action="" method="POST" name="formProject">
                <table align = "center" cellpadding = "15" border = "1">
                    <tr>
                        <td> 
                            <img src="http://latex.codecogs.com/gif.latex?Main function" border="0"/>
                        </td>
                        <td align = "center"> 
                            <img src="http://latex.codecogs.com/gif.latex?y(x) = \frac{1}{1+\alpha (x-x_0)^2} + \frac{c}{1+\alpha (x-x_0-\delta)^2}" border="0"/>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <img src="http://latex.codecogs.com/gif.latex?\alpha" border="0"/>
                        </td>
                        <td align = "center"> 
                            <input type = "text" name = "alpha" value = "<?php
                            if (isset($_POST['alpha'])) {
                                echo $_POST['alpha'];
                            }
                            ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <img src="http://latex.codecogs.com/gif.latex?\delta" border="0"/>
                        </td>
                        <td align = "center"> 
                            <input type="text" name = "delta"   value = "<?php
                            if (isset($_POST['delta'])) {
                                echo $_POST['delta'];
                            }
                            ?>" step="1"/>
                        </td>
                    </tr>				
                    <tr>
                        <td> 
                            <img src="http://latex.codecogs.com/gif.latex?c" border="0"/>
                        </td>
                        <td align = "center"> 
                            <input type = "text" name = "c" value = "<?php
                            if (isset($_POST['c'])) {
                                echo $_POST['c'];
                            }
                            ?>" step="1"/>
                        </td>
                    </tr>
                </table>
                <table  align = "center" cellpadding = "10">
                    <tr>
                        <td colspan = "4"> 

                            <button name = "btn_calcular" type="submit" style="width: 250px;height: 30px;">
                                <img src="http://latex.codecogs.com/gif.latex?Calculate" border="0"/>
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>
    <br><br>
</head>

<body style = "background-color:#f0f1ff;color:#000000;font-family:arial">
    <?php
    const epsilon = 1e-15;
    const xSubZero = 4122.44898;
    //const xSubZero = 4095.69378;
    $y = "1/(1 + a*(x-m)**2) + c/(1 + a*(x-m-d)**2)";
    //$y = "1/(1+a*pow((x-m),2)) + c/(1+a*pow((x-m-d),2))";
    $jacobian = array("(-(x - m)**2 /(1 + a*(x - m)**2)**2) - ((c*(x-m-d)**2)/(1+a*(x-m-d)**2)**2)", "(2*a*c*(x-m-d))/(1+a*(x-m-d)**2)**2", "1/(1+a*(x-m-d)**2)"); //alpha,delta,c

    if (isset($_POST['btn_calcular'])) {
        $alpha = $_POST['alpha'];
        $delta = $_POST['delta'];
        $c = $_POST['c'];
        $z = calculateSol($alpha, $delta, $c);
        
        makeGraph($y, $z);
    }

    function pointXY($index) {
        $vectorX = array(
            1400, 1600, 1800, 2000, 2200,
            2400, 2600, 2800, 3000, 3200,
            3400, 3600, 3800, 4000, 4200,
            4400, 4600, 4800, 5000, 5200,
            5400, 5600, 5800, 6000, 6200,
            6400);
//        $vectorX = array(-1000, -800, -600, -400, -200, 0, 200, 400, 600, 800,
//            1000, 1200, 1400, 1600, 1800, 2000, 2200, 2400, 2600,
//            2800, 3000, 3200, 3400, 3600, 3800, 4000, 4200, 4400,
//            4600, 4800, 5000, 5200, 5400, 5600, 5800, 6000, 6200,
//            6400, 6600, 6800, 7000, 7200, 7400, 7600, 7800, 8000,
//            8200, 8400, 8600, 8800);
        $vectorY = array(
            16.4119066773934, 42.4778761, 82.0595334, 125.502816, 197.908286,
            304.1029767, 419.9517297, 516.4923572, 641.995173, 772.3250201,
            891.069992, 977.9565567, 1055.189059, 1108.286404, 1110.217216,
            1066.773934, 999.1954948, 904.5856798, 755.9131134, 611.1021722,
            480.7723250, 299.2759453, 212.3893805, 142.8801287, 80.12872084,
            28.96218825);
//        $vectorY = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2.068965517,
//            20.68965517, 41.37931034, 82.75862069, 134.4827586, 196.5517241, 289.6551724, 393.1034483, 517.2413793,
//            651.7241379, 786.2068966, 879.3103448, 982.7586207, 1055.172414, 1106.896552, 1106.896552, 1075.862069,
//            982.7586207, 889.6551724, 744.8275862, 620.6896552, 455.1724138, 320.6896552, 206.8965517,
//            134.4827586, 72.4137931, 22.75862069, 2.068965517, 0,
//            0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $vectorPoint = array($vectorX[$index], $vectorY[$index]);
        return $vectorPoint;
    }

    function normalizedCount($index) {
        /*$vector = array(0.014655172, 0.037931034, 0.073275862, 0.112068966, 0.176724138,
            0.271551724, 0.375, 0.461206897, 0.573275862, 0.689655173, 0.795689655,
            0.873275862, 0.942241379, 0.989655173, 1, 0.99137931, 0.952586207, 0.892241379,
            0.807758621, 0.675, 0.545689655, 0.429310345, 0.267241379, 0.189655172, 0.127586207, 0.071551724, 0.025862069);*/
        $vector = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            0.10582, 0.1208,
            0.13899, 0.16121, 0.18831, 0.22102, 0.25958, 0.30368, 0.35336, 0.41085, 0.48118, 0.57007, 0.68, 0.80512,
            0.92513, 1.00475, 1.01063, 0.93835, 0.81647, 0.68169, 0.55802, 0.45458, 0.37174, 0.30652, 0.25532,
            0.21494, 0.18282, 0.15702, 0.13609, 0.11892,
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        return $vector[$index];
    }

    function gauss_jordan($matrix, $n, $b) {

        for ($k = 0; $k < $n - 1; $k++) {
            for ($i = $k + 1; $i < $n; $i++) {
                $m = $matrix[$i][$k] / $matrix[$k][$k];
                $b[$i][0] -= $m * $b[$k][0];
                $matrix[$i][$k] = 0;
                for ($j = $k + 1; $j < $n; $j++) {
                    $matrix[$i][$j] -= $m * $matrix[$k][$j];
                }
            }
        }

        for ($g = $n - 1; $g >= 0; $g--) {
            $sum = 0;
            for ($p = $g + 1; $p < $n; $p++) {
                $sum += $matrix[$g][$p] * $x[$p];
            }
            $x[$g] = 1 / $matrix[$g][$g] * ($b[$g][0] - $sum);
        }

        return $x;
    }

    function eval_analytic_jacobian($alpha, $delta, $c, $x, $x0) {
        global $jacobian;
        $resl = array();
        $auxFunc = array();
        for ($i = 0; $i < count($jacobian); $i++) {
            $auxFunc = $jacobian;
            $auxFunc[$i] = str_replace("a", "($alpha)", $auxFunc[$i]);
            $auxFunc[$i] = str_replace("d", "($delta)", $auxFunc[$i]);
            $auxFunc[$i] = str_replace("c", "($c)", $auxFunc[$i]);
            $auxFunc[$i] = str_replace("x", "($x)", $auxFunc[$i]);
            $auxFunc[$i] = str_replace("m", "($x0)", $auxFunc[$i]);
            $res = "";
            eval("\$res=" . $auxFunc[$i] . ";");
            $resl[$i] = $res;
        }
        return $resl;
    }

    function eval_y($alpha, $delta, $c, $x, $x0) {
        global $y;
        $auxFunc = $y;
        $auxFunc = str_replace("a", "($alpha)", $auxFunc);
        $auxFunc = str_replace("d", "($delta)", $auxFunc);
        $auxFunc = str_replace("c", "($c)", $auxFunc);
        $auxFunc = str_replace("x", "($x)", $auxFunc);
        $auxFunc = str_replace("m", "($x0)", $auxFunc);
        $res = "";
        eval("\$res=" . $auxFunc . ";");
        return $res;
    }

    function replace($function, $alpha, $delta, $c, $x, $x0) {
        $auxFunc = $function;
        $auxFunc = str_replace("a", "($alpha)", $auxFunc);
        $auxFunc = str_replace("d", "($delta)", $auxFunc);
        $auxFunc = str_replace("c", "($c)", $auxFunc);
        $auxFunc = str_replace("x", "($x)", $auxFunc);
        $auxFunc = str_replace("m", "($x0)", $auxFunc);
        $res = "";
        eval("\$res=" . $auxFunc . ";");
        return $res;
    }

    function eval_dif($function, $alpha, $delta, $c, $x, $x0, $compare) {
        //"1/(1 + a*(x-m)*2) + c/(1 + a(x-m-d)**2)"
        global $y;
        $variation = 1e-7;
        $answer = 0;
        $prev_ans = 0;
        do {
            $prev_ans = $answer;
            if ($compare == "alpha") {
                $val1 = replace($y, $alpha + $variation, $delta, $c, $x, $x0);
                $val2 = replace($y, $alpha - $variation, $delta, $c, $x, $x0);
            }
            if ($compare == "delta") {
                $val1 = replace($y, $alpha, $delta + $variation, $c, $x, $x0);
                $val2 = replace($y, $alpha, $delta - $variation, $c, $x, $x0);
            }
            if ($compare == "c") {
                $val1 = replace($y, $alpha, $delta, $c + $variation, $x, $x0);
                $val2 = replace($y, $alpha, $delta, $c - $variation, $x, $x0);
            }

            $answer = ($val1 - $val2) / (2 * $variation);
            $variation /= 2;

            if (abs($answer - $prev_ans) < epsilon)
                break;
        }while (true);
        return $answer;
    }

    function transpose($A) {
        $B = array();
        for ($i = 0; $i < count($A[$i]); $i++) {
            for ($j = 0; $j < count($A); $j++) {
                $B[$i][$j] = $A[$j][$i];
            }
        }
        return $B;
    }

    function get_diag($matrix) {
        $diag = array(array());
        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                if ($i == $j) {
                    $diag[$i][$j] = $matrix[$i][$j];
                } else {
                    $diag[$i][$j] = 0;
                }
            }
        }
        return $diag;
    }

    function multiply($a, $b) {
        for ($i = 0; $i < count($a); $i++) {
            for ($j = 0; $j < count($b[$i]); $j++) {
                $mult[$i][$j] = 0;
                for ($k = 0; $k < count($a[$i]); $k++) {
                    $mult[$i][$j] += $a[$i][$k] * $b[$k][$j];
                }
            }
        }
        return $mult;
    }

    function adition($a, $b) {
        for ($i = 0; $i < count($a); $i++) {
            for ($j = 0; $j < count($a[$i]); $j++) {
                $add[$i][$j] = $a[$i][$j] + $b[$i][$j];
            }
        }
        return $add;
    }

    function scalarMult($a, $b) {
        for ($i = 0; $i < count($a); $i++) {
            for ($j = 0; $j < count($a[$i]); $j++) {
                $mult[$i][$j] = $a[$i][$j] * $b;
            }
        }
        return $mult;
    }

    function print_matrix($matrix) {
        for ($i = 0; $i < count($matrix); $i++) {
            print_r($matrix[$i]);
            echo "<br>";
        }
    }

    function calculateSol($alpha, $delta, $c) {
        global $y;
        $z_in = array($alpha, $delta, $c);
        $z_out = array($alpha, $delta, $c);
        $lambda = 1;
        $identity = array(array(1, 0, 0), array(0, 1, 0), array(0, 0, 1));
        $f = array();
        $analyticAns = array();
        $numericJacobian = array();
        $prev_s = 0;
        $counter = 0;
        echo '<div id = "tablespace" align = "center"><fieldset align ="center" style = "display:inline-block;width: 1500px;"><legend>Answer</legend>';
        echo '<font size = "2">';
        echo "<table border = 1 style = font-family:sans-serif cellpadding = 5>";
        echo '<th><img src="http://latex.codecogs.com/gif.latex?Iteration" border="0"/></th>'
        . '<th> <img src="http://latex.codecogs.com/gif.latex?\alpha" border="0"/></th>'
        . '<th> <img src="http://latex.codecogs.com/gif.latex?\delta" border="0"/></th>'
        . '<th> <img src="http://latex.codecogs.com/gif.latex?c" border="0"/></th>'
        . '<th> <img src="http://latex.codecogs.com/gif.latex?Functional" border="0"/></th>';
        echo "<tr>";
        echo "<td> - </td>";
        echo "<td> $z_in[0] </td>";
        echo "<td> $z_in[1] </td>";
        echo "<td> $z_in[2] </td>";
        echo "<td> - </td>";
        echo "</tr>";
        do {
            for ($i = 0; $i < 26; $i++) {
                $aux = pointXY($i);
                $f[$i][0] = eval_y($z_out[0], $z_out[1], $z_out[2], $aux[0], xSubZero) - ($aux[1] / 1117.241379);
            }

            $f_transpose = transpose($f);
            $s = multiply($f_transpose, $f);

            for ($i = 0; $i < 26; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    $aux = pointXY($i);
                    if ($j == 0) {
                        $numericJacobian[$i][$j] = eval_dif($y, $z_out[0], $z_out[1], $z_out[2], $aux[0], xSubZero, "alpha");
                    }
                    if ($j == 1) {
                        $numericJacobian[$i][$j] = eval_dif($y, $z_out[0], $z_out[1], $z_out[2], $aux[0], xSubZero, "delta");
                    }
                    if ($j == 2) {
                        $numericJacobian[$i][$j] = eval_dif($y, $z_out[0], $z_out[1], $z_out[2], $aux[0], xSubZero, "c");
                    }
                }
            }
            if ($s[0][0] <= (($prev_s[0][0]) / 2)) {
                $lambda /= 2;
            } else {
                $lambda *= 2;
            }
            $prev_s = $s;

            $jacobian_transpose = transpose($numericJacobian);
            $auxA = multiply($jacobian_transpose, $numericJacobian);
            $diag = get_diag($auxA);
            $auxB = scalarMult($identity, $lambda);
            // $auxB = scalarMult($diag, $lambda);     //  Marquardt
            $matrix = adition($auxA, $auxB);
            $auxC = scalarMult($jacobian_transpose, -1);
            $b = multiply($auxC, $f);
            $del_z = gauss_jordan($matrix, 3, $b);


            for ($i = 0; $i < count($del_z); $i++) {
                $z_out[$i] += $del_z[$i];
            }

            $continua = true;
            $cont = 0;
            if (abs($z_out[0] - $z_in[0]) < 1e-6)
                $cont++;
            if (abs($z_out[1] - $z_in[1]) < 1e-6)
                $cont++;
            if (abs($z_out[2] - $z_in[2]) < 1e-6)
                $cont++;
            if ($cont == 3)
                $continua = false;
            $z_in = $z_out;
            $counter++;
            echo "<tr>";
            echo "<td> $counter </td>";
            echo "<td> $z_out[0] </td>";
            echo "<td> $z_out[1] </td>";
            echo "<td> $z_out[2] </td>";
            echo "<td>", $s[0][0], "</td>";
            echo "</tr>";
        } while ($continua);
        echo '</table></font></fieldset></div>';
        echo '<br><br>';
        printJacobian($z_out[0], $z_out[1], $z_out[2]);
        printCompTable($z_out[0], $z_out[1], $z_out[2]);
        return $z_out;
    }

    function printJacobian($alpha, $delta, $c) {
        global $y;
        $analyticAns = array();
        echo '<div id = "tablespace" align = "center"><fieldset align ="center" style = "display:inline-block;width: 1500px;"><legend>Data</legend>';
        echo '<font size = "2">';
        echo "<table border = 1 style = font-family:sans-serif cellpadding = 5>";
        echo '<tr><th><img src="http://latex.codecogs.com/gif.latex?Iteration" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?X_i" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?Y_i" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Normalized\enspace Count" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Numerical\enspace \alpha" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Analytic\enspace \alpha" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Numerical\enspace \delta" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Analytic\enspace \delta" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Numerical\enspace c" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex? Analytic\enspace c" border="0"/></th>';
        for ($i = 0; $i < 26; $i++) {
            $aux = pointXY($i);
            $analyticAns = eval_analytic_jacobian($alpha, $delta, $c, $aux[0], xSubZero);
            $ansNumericAlpha = eval_dif($y, $alpha, $delta, $c, $aux[0], xSubZero, "alpha");
            $ansNumericDelta = eval_dif($y, $alpha, $delta, $c, $aux[0], xSubZero, "delta");
            $ansNumericC = eval_dif($y, $alpha, $delta, $c, $aux[0], xSubZero, "c");
            echo "<tr>";
            echo "<td> $i </td>";
            echo "<td> $aux[0] </td>";
            echo "<td> $aux[1] </td>";
            echo "<td>", normalizedCount($i), "</td>";
            echo "<td> $ansNumericAlpha </td>";
            echo "<td> $analyticAns[0] </td>";
            echo "<td> $ansNumericDelta </td>";
            echo "<td> $analyticAns[1] </td>";
            echo "<td> $ansNumericC </td>";
            echo "<td> $analyticAns[2] </td>";
            echo "</tr>";
        }
        echo '</table></font></fieldset></div>';
        echo "<br><br>";
    }

    function printCompTable($alpha, $delta, $c) {
        global $y;
        $analyticAns = array();
        echo '<div id = "tablespace" align = "center"><fieldset align ="center" style = "display:inline-block;width: 1500px;"><legend>Comparative data</legend>';
        echo '<font size = "2">';
        echo "<table border = 1 style = font-family:sans-serif cellpadding = 5>";
        echo '<tr><th><img src="http://latex.codecogs.com/gif.latex?Iteration" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?X_i" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?\overline{y_i}" border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?y(x_i) \enspace exp." border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?y(x_i) \enspace calc." border="0"/></th>'
        . '<th><img src="http://latex.codecogs.com/gif.latex?Difference \enspace \%" border="0"/></th>';


        for ($i = 0; $i < 26; $i++) {
            $aux = pointXY($i);
            $evaly = eval_y($alpha, $delta, $c, $aux[0], xSubZero);
            echo "<tr>";
            echo "<td> $i </td>";
            echo "<td> $aux[0] </td>";
            echo "<td> $aux[1] </td>";
            echo "<td>", normalizedCount($i), "</td>";
            echo "<td>", $evaly, " </td>";
            echo "<td>", (abs(normalizedCount($i) - $evaly) / $evaly)*100, " </td>";
            echo "</tr>";
        }
        echo '</table></font></fieldset></div>';
        echo "<br><br>";
    }

    function makeGraph($function, $z) {
        $auxFunc = $function;
        $auxFunc = str_replace("a", "($z[0])", $auxFunc);
        $auxFunc = str_replace("d", "($z[1])", $auxFunc);
        $auxFunc = str_replace("c", "($z[2])", $auxFunc);
        $auxFunc = str_replace("m", xSubZero, $auxFunc);
        echo '<div class="h4" align = "center"> <fieldset align = "center" style = "display:inline-block;width: 1500px;"> <legend>Graph</legend>'
        . '<img src="graph.php?tipo=1&funcion=' . urlencode($auxFunc) . '"></div></fieldset>';
    }
    ?>
</body>
</html>

