<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Search | PIBAS FedSPARQL</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />



        <link rel="stylesheet" type="text/css" href="css/tablesort.css">
            <link rel="stylesheet" type="text/css" href="css/pop-up-window.css">
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
                    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                        <link rel="stylesheet" type="text/css" href="styles.css" />
                        <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script> 


                        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
                         <!--                <script src="//code.jquery.com/jquery-1.12.3.js"></script>-->

                        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                        <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

                        <script type="text/javascript" src="js/tablesort.js"></script> 


                        <script type="text/javascript" src="js/getSubtopics.js"></script>
                        <script type="text/javascript" src="js/getTemplates.js"></script>
                        <script type="text/javascript" src="js/getKeywordName.js"></script>
                        <script type="text/javascript" src="js/runAll.js"></script>
                        <script type="text/javascript" src="js/filter.js"></script>
                        <script type="text/javascript" src="js/getDatasetProperties.js"></script>
                        <script type="text/javascript" src="js/selectProperty.js"></script>
                        <script type="text/javascript" src="js/runFiltredQuery.js"></script>
                        <script type="text/javascript" src="js/addNewDataset.js"></script>
                        <script type="text/javascript" src="js/popWindowShow.js"></script>
                        <script type="text/javascript" src="js/findSimilarItems.js"></script>
                        <script type="text/javascript" src="js/pop-up-window-statistics.js"></script>
                        <script>
                            $(window).load(function () {
                                // PAGE IS FULLY LOADED  
                                // FADE OUT YOUR OVERLAYING DIV
                                $('#wait_for_beginning').fadeOut('fast');
                            });
                        </script>
						<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60532004-2', 'auto');
  ga('send', 'pageview');

</script>
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

                                    <input type="hidden" id="template_topic" name="template_topic" value=""/>


                                    <div id="conteiner" style="height: auto; font-style: inherit;">


                                        <h1>Bioinformatics Data Search</h1><br/><br/>
                                        <form name="search" method="POST">
                                            <div id="wait_for_beginning" style="text-align: center;display:yes;width:139px;height:139px;margin: auto;top:30%;padding:10px;"><img src='./images/loading.gif' width="104" height="104" /><br>Loading..</div>


                                            <table style="font-size: 15px; font-weight: bold;border: none; table-layout:fixed;border-color:#000000;">
                                                <col width="100px" />
                                                <col width="100px" />
                                                <col width="100px" />
                                                <col width="100px" />
                                                <col width="100px" />
                                                <col width="100px" />
                                                <tr>
                                                    <td><label id="topics" style="display: none;">Select topic</label></td>
                                                    <td><label id="subtopics" style="display: none;">Select subtopic</label></td>
                                                    <td><label id="templates" style="display: none;">Select template</label></td>
                                                    <td><label id="keywords" style="display: none;"></label></td>
                                                </tr>
                                                <tr>

                                                    <td>
                                                        <?php include ('php/getTopics.php'); ?>

                                                    </td>
                                                    <td>
                                                        <select id="get_subtopics" style="display: none;width: 170px;" onchange="getTemplates();">

                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="get_templates" style="display: none;" onchange="getKeywordName();">

                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="get_keywords" style="display: none;" size="40">

                                                    </td>
                                                    <td>
                                                        <input type="button" value="Run query" id="run_all" style="display: none;background-color:white;" onclick="RunAll();"/>
                                                    </td>

                                                </tr>

                                            </table>
                                            <div id="wait" style="text-align: center;display:none;width:139px;height:139px;margin: auto;top:50%;left:50%;right:50px;padding:10px;"><img src='./images/loading.gif' width="104" height="104" /><br>Loading..</div>

                                            <br/>
                                            <br/>
                                            <table>


                                                <tr>
                                                    <td>
                                                        <input type="button" value="Filter query" id="filter_query" style="display: none;" onclick="Filter();"/>
                                                    </td>
                                                    <td>
                                                        <input type="button" name="add_new_datasets" id="add_new_datasets"  style="display: none;" value="Add new dataset" onclick="showData();"/>

                                                    </td>
                                                    <td>
                                                        <input type="button" value="Detect similar data" id="finding_similar_data_items" name="finding_similar_data_items" style="display: none;" onclick="FindSimilarItems();"/>
                                                    </td>

                                                </tr>
                                            </table>
											
											

                                            <br/>



                                            <div id="myModal" style="display: none;" class="modal">

                                                <!--                                                 Modal content -->

                                            </div>

                                            <br/>  

                                            <table style="border: none; width:100%" >
                                                <tr><td>
                                                        <div id="questionmark" style="display: none;"> 
                                                            <img src="./images/dataAnalysis.jpg" id="statistics_data" align="right" style="width: 30px;height: 30px;" onclick="showDataForStatistics();"></img>
                                                        </div>
                                                    </td>
                                                </tr> 
                                                <tr>
                                                    <td>
                                                        <div id="myDynamicTable" style="width: 1014px;word-break: break-all;">

                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div id="my_accordion">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                              
                                            <div id="wait_for_adding_dataset" style="text-align: center; display:none;width:200px;height:200px;margin: auto;top:50%;left:50%;right: 50%;padding:10px;position: absolute;z-index:2;"><img src="./images/loading.gif" width="104" height="104" /><br>Loading..</div>
        
                                            <div id="for_statistics_data" class="modal" stlye="display:none;">
                                                <div class="modal-content" id="modal-con">
                                                    <span class="close">x</span>
                                                    <table style="border-style: solid;border: #000;text-align: center;"id="statistics_data_show_table" width="1000px;">
                                                        
                                                    </table>
                                                </div>
                                            </div>

                                           
                                            <input type="hidden" id="property_array" value=""/>

                                            <input type="hidden" id="for_new_dataset" value=""/>
                                            <input type="hidden" id="for_filter" value=""/><!--
                                            -->                                            <input type="hidden" id="new_prefix" value=""/>


                                            <input type="hidden" id="for_similar_endpoints" value=""/>
                                            <input type="hidden" id="for_similar_initiatives" value=""/>
                                            <input type="hidden" id="for_similar_properties" value=""/>


                                            <input type="hidden" id="for_filter_initial_query" value=""/>

                                            <input type="hidden" id="for_new_filter" value=""/>

                                            <input type="hidden" id="name_of_dataset_initiative" value=""/>


                                            <br/>


                                        </form>



                                    </div>
                                    <!-- content ends -->
                                    <!-- footer begins -->
                                    <div id="footer">
                                        Copyright  2016. Designed by <a href="http://cpctas-lcmb.pmf.kg.ac.rs" title="CPCTAS">CPCTAS team</a>	
                                        <br/>
                                        <a href="http://imi.pmf.kg.ac.rs" target='_blank'>IMI</a> | <a href="http://www.pmf.kg.ac.rs/" target='_blank'>PMF</a> | <a href="http://www.kg.ac.rs/" target='_blank'>University of Kragujevac</a>
                                    </div>
                                </div>
                        </body>
                        </html>
