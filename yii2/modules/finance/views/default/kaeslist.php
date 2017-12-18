<?php 

$columnsNum = 3;
$kaesCount = count($kaes);
$kaesSubListCount = ceil($kaesCount/$columnsNum);
$kaesListDivide = array();

if($kaesCount != 0):
    for($i = 0; $i < $columnsNum; $i++)
        for($j = 0; $j < $kaesSubListCount; $j++)
        {
            if(($kaesSubListCount*$i + $j) >= $kaesCount)
                break;
                $kaesListDivide[$i][$j] = $kaes[$kaesSubListCount*$i + $j];
        }
?>
    
    <p style="text-align: right;">
        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#kaesList">
        	<?php echo $btnLiteral; ?>
        </button>
    </p>
    <div id="kaesList" class="collapse">
        <div class="container-fluid well">
      		<div class="row">
      			<?php foreach ($kaesListDivide as $kaeList) : ?>
      						<div class="col-lg-<?php echo 12/$columnsNum; ?>">
      							<?php foreach ($kaeList as $kaeListItem): ?> 
                                        <p><a href='<?php echo $actionUrl; ?>?id=<?php echo $kaeListItem->kae_id; ?>'><span class="label label-primary"><?= $kaeListItem->kae_id; ?></span>
                                        <?php echo $kaeListItem->kae_title;?></a>
                                        </p>
                                <?php endforeach;?>
      						</div>		
      			<?php endforeach;?>      			
    		</div>
    	</div>
    </div>
<?php endif;?>