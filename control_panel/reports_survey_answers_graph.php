<?php require_once '../includes/util.php'; ?>
<?php require_once '../widget/jpgraph/src/jpgraph.php'; ?>
<?php require_once '../widget/jpgraph/src/jpgraph_pie.php'; ?>
<?php require_once '../widget/jpgraph/src/jpgraph_pie3d.php'; ?>
<?php require_once '../widget/jpgraph/src/jpgraph_bar.php'; ?>
<?php 
/*
if(isset($_REQUEST['data']))
{
// Some data
$data = unserialize($_REQUEST['data']);

// Create the Pie Graph. 
$graph = new PieGraph(350,250);

$theme_class="DefaultTheme";
//$graph->SetTheme(new $theme_class());

// Set A title for the plot
$graph->title->Set("A Simple Pie Plot");
$graph->SetBox(true);

// Create
$p1 = new PiePlot($data);
$graph->Add($p1);

$p1->ShowBorder();
$p1->SetColor('black');
$p1->SetSliceColors(array('#1E90FF','#2E8B57'));
$graph->Stroke();
}
*/
?>
<?php 
switch(trim($_GET['type']))
{
	case 'pie3d':
	
		// Some data
		$data = unserialize(html_entity_decode(urldecode($_GET['data'])));
		$leg = unserialize(html_entity_decode(urldecode($_GET['leg'])));
		$title_get = (isset($_GET['title']))?unserialize(html_entity_decode(urldecode($_GET['title']))):"";
		$width = (isset($_GET['width']))?html_entity_decode(urldecode($_GET['width'])):"400";
		$height = (isset($_GET['height']))?html_entity_decode(urldecode($_GET['height'])):"200";
		$theme = (isset($_GET['theme']))?html_entity_decode(urldecode($_GET['theme'])):"earth";
		
		// Create the Pie Graph.
		$graph = new PieGraph($width,$height);
		$graph->ClearTheme();
		$graph->SetShadow();

		$title = (isset($_GET['title']) && !empty($_GET['title'])) ? $title_get : "";
		
		// Set A title for the plot
		$graph->title->Set($title);
		//$graph->title->SetFont(FF_VERDANA,FS_BOLD,18); 
		$graph->title->SetColor("black");
		$graph->legend->Pos(0.05,0.1);
		
		// Create pie plot
		$p1 = new PiePlot3d($data);
		
		$p1->value->HideZero();
		$p1->SetTheme($theme);
		$p1->SetCenter(0.4);
		$p1->SetAngle(40);
		//$p1->value->SetFont(FF_ARIAL,FS_NORMAL,10);
		//$p1->SetSliceColors(array('black','#2E8B57'));
		$p1->SetLegends($leg);
		//$p1->ExplodeAll(10);

		$graph->Add($p1);
		$graph->Stroke();
		
		break;
	
	case 'verticalbar':
	
		// Some data
		$datay = unserialize(html_entity_decode(urldecode($_GET['y-data'])));
		$datax = unserialize(html_entity_decode(urldecode($_GET['x-data'])));
		$x_title = (isset($_GET['x-title']))?html_entity_decode(urldecode($_GET['x-title'])):"";
		$y_title = (isset($_GET['y-title']))?html_entity_decode(urldecode($_GET['y-title'])):"";
		$title_get = (isset($_GET['title']))?html_entity_decode(urldecode($_GET['title'])):"";
		$width = (isset($_GET['width']))?html_entity_decode(urldecode($_GET['width'])):"400";
		$height = (isset($_GET['height']))?html_entity_decode(urldecode($_GET['height'])):"200";
		$theme = (isset($_GET['theme']))?html_entity_decode(urldecode($_GET['theme'])):"earth";

		// Create the Bar Graph
		$graph = new Graph($width, $height, 'auto');
		$graph->ClearTheme();
		
		$graph->SetMargin(50,30,30,50);
		$graph->SetShadow(true, 5);
		$graph->SetScale("textlin");
		//$graph->yscale->ticks->Set(40,2);
		
		
		//	$graph->yaxis->scale->SetGrace(100);
		$graph->xaxis->SetTickLabels($datax);
		//$graph->yaxis->SetTickSize(3,1);
		$graph->yaxis->scale->ticks->SupressTickMarks();
		$graph->xaxis->scale->ticks->SupressTickMarks();
		//$graph->yaxis->SetTextLabelInterval(1);
		
		//$graph->yaxis->SetLabelFormatString("%d");
		//$graph->yaxis->scale->labels->SetColor("black");
		
		$graph->yaxis->title->Set($y_title);
		$graph->xaxis->title->Set($x_title);
		
		$graph->yaxis->SetColor('#cccccc', 'black');
		$graph->xaxis->SetColor('#cccccc', 'black');
		
		$graph->yaxis->title->SetPos(0,0, 'left', 'center');
		
		
		$title = (isset($_GET['title']) && !empty($_GET['title'])) ? $title_get : "";
		
		// Create bar plot
		$bplot = new BarPlot($datay);
		
		// Set color for the frame of each bar
		$bplot->SetColor("#61a9f3");
		$bplot->SetFillColor("#61a9f3");
		$bplot->SetWidth(0.4);
		$graph->Add($bplot);

		// Finally send the graph to the browser
		$graph->Stroke();
		
	
		break;
}
?>

