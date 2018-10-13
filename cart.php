<?php
session_start();
$pids=array();
//session_destroy();

//check if add to cart button has been submitted
if(filter_input(INPUT_POST,'add_TO_cart'))
{
	if(isset($_SESSION['shoppingcart']))
	{
		 //keep trackk
		 $count=count($_SESSION['shoppingcart']);
		 $pids= array_column($_SESSION['shoppingcart'], 'pids');
		 if(!in_array(filter_input(INPUT_GET,'id'),$pids)){
			 $_SESSION['shoppingcart'][$count]=array
		(
		   'id' =>filter_input(INPUT_GET,'id'),
		   'name' =>filter_input(INPUT_POST,'name'),
		   'price' =>filter_input(INPUT_POST,'price'),
		   'quantity' =>filter_input(INPUT_POST,'quantity'),
		   );
			 
		 }
		 else{ //product already exits
			 for($i=0;$i<count($pids);$i++)
			 {
				 if($pids[$i] ==filter_input(INPUT_GET,'id'))
				 { 
			 //
					 $_SESSION['shoppingcart'][$i]['quantity'] +=filter_input(INPUT_POST,'quantity');
				 }
			 }
		 }
		 
	}
	else
	{
		$_SESSION['shoppingcart'][0]=array
		(
		   'id' =>filter_input(INPUT_GET,'id'),
		   'name' =>filter_input(INPUT_POST,'name'),
		   'price' =>filter_input(INPUT_POST,'price'),
		   'quantity' =>filter_input(INPUT_POST,'quantity'),
		   );
		   
	}
}
pre_r($_SESSION);
function pre_r($array)
{
  echo '<pre>';
  print_r($array);
	echo '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Online Medical Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet"/>
    <!-- Customize styles -->
    <link href="style.css" rel="stylesheet"/>
    <!-- font awesome styles -->
	<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
		<!--[if IE 7]>
			<link href="css/font-awesome-ie7.min.css" rel="stylesheet">
		<![endif]-->

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	<!-- Favicons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
  </head>
<body>
	<div class="container">
	
<?php
// Connect to the database
$con = mysqli_connect("localhost","root","","demo");
$query="SELECT * FROM ayurvedic order by id ASC";
$result=mysqli_query($con,$query);
if ($result):
if(mysqli_num_rows($result)>0):
while($products=mysqli_fetch_assoc($result)):
//print_r($products);

?>
<div class="col-sm-4 col-md-3">
	<form method="post" action="cart.php?action=add&pid=<?php echo $products['id'];?>">
		<div class="pro">
			<img src="<?php echo $products['image']; ?>" class="img-responsive"/>
			<h4 class="text-info"><?php echo $products['name'];?></h4>
			<h4>RS <?php echo $products['price'];?></h4>
			<input type="text" name="quantity" class="form-control" value="1" />
			<input type="hidden" name="name" value="<?php echo $products['name'];?>" />
			<input type="hidden" name="price" value="<?php echo $products['price'];?>" />
			<input type="submit" name="add_TO_cart" class="btn btn-info" value=" Add to cart" />
			</div>
			</form>
			</div>
			<?php 
			endwhile;
			endif;
			endif;
			?>
			<div style="clear:both"></div>
				<table class="table">
					<tr><th colspan="5"><h3> order details</h3></th></tr>
					<tr>
						<th width="40%">product name</th>
						<th width="10%">quantity</th>
						<th width="20%">price</th>
						<th width="15%">total</th>
						<th width="5%">action</th>
					</tr>
						<?php
						if(!empty($_SESSION['shoppingcart'])):
						  $total=0;
						   foreach($_SESSION['shoppingcart'] as $key =>$products):
						   ?>
						   <tr>
								<td><?php echo $products['name']; ?></td>
								<td><?php echo $products['quantity']; ?></td>
								<td><?php echo $products['price']; ?></td>
								<td><?php echo number_format($products['quantity']*$products['price'],2); ?></td>
								<td> <a href="cart.php?action=delete&pid=<?php echo $products['id'];?>">
								  <div class="btn-danger">Remove</div>
								  </a>
								  </td>
								  </tr>
								  <?php
								     $total=$total +($products['quantity']*$products['price']);
									 endforeach;
									 ?>
									 <tr>
									 <td colspan="3" align="right">total</td>
									 <td align="right">RS <?php echo number_format($total,2);?></td>
									 <td></td>
									 </tr>
									 <tr>
										<td colspan="5">
										<?php 
										if(isset($_SESSION['shoppingcart'])):
										if(count($_SESSION['shoppingcart'])>0):
										?>
										<a href="#" class="button">checkout</a>
										<?php endif;endif;?>
										</td>
										</tr>
										<?php
										endif;
										?>
										</table>
										</div>
										</div>
										</body>
										</html>