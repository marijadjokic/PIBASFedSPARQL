<?php
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Similar items | PIBAS FedSPARQL</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />

        <link href="styles.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/tablesort.css">
            <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
            <script src="//code.jquery.com/jquery-1.10.2.js"></script>
            <script type="text/javascript" src="js/tablesort.js"></script> 


    </head>
    <body>



        <div id="main_bot">
            <div id="main">
                <!-- header begins -->
                <div id="header">
                    <div id="buttons">
                        <div ><a href="index.html" class="but"  title="">Home</a></div>
                        <div><a href="userguide.php"  class="but" title="">User Guide</a></div>
                        <div><a href="search.php" class="but" title="">Search</a></div>
                        <div><a href="references.html"  class="but" title="">References</a></div>
                        <div ><a href="contact.html" class="but" title="">Contact us</a></div>
                    </div>
                    <div id="logo">PIBAS FedSPARQL
                        <h2><a href="http://cpctas-lcmb.pmf.kg.ac.rs" id="metamorph" target="_blank">Design by CPCTAS team</a></h2>
                    </div>
                </div>
                <!-- header ends -->
                <!-- content begins -->



                <div id="conteiner" style="height: auto; font-style: inherit;">
                    <h1>Similar Items</h1><br/><br/>
                    <form name="search" method="POST">

                        <div id="show_similar_items">
                        </div>
                         <input type="hidden" id="simlar_data" value="<?php echo $_SESSION['similar']; ?>"/>
                        <?php echo ' <script type="text/javascript" src="js/showSimilarItems.js"></script>'; ?>

                    </form>

                </div>
                <!-- content ends -->
                <!-- footer begins -->
                <div id="footer">
                    Copyright  2019. Designed by <a href="http://cpctas-lcmb.pmf.kg.ac.rs" title="CPCTAS">CPCTAS team</a>	
                    <br/>
                    <a href="imi.pmf.kg.ac.rs" target='_blank'>IMI</a> | <a href="http://www.pmf.kg.ac.rs/" target='_blank'>PMF</a> | <a href="http://www.kg.ac.rs/" target='_blank'>University of Kragujevac</a>
                </div>
            </div>
    </body>
</html>
