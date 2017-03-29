<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>User Guide | PIBAS FedSPARQL</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <link rel="stylesheet" type="text/css" href="./css/showBigImages.css" />
        <script type="text/javascript" src="./js/showBigImages.js"></script>
    </head>
    <body>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
			<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60532004-2', 'auto');
  ga('send', 'pageview');

</script>

        <div id="main_bot">
            <div id="main">
                <!-- header begins -->
                <div id="header">
                    <div id="buttons">
                        <div ><a href="index.html" class="but"  title="">Home</a></div>
                        <div><a href="userguide.php"  class="but" title="">User Guide</a></div>
                        <div ><a href="search.php" class="but" title="">Search</a></div>
                        <div><a href="references.html"  class="but" title="">References</a></div>                        
                        <div ><a href="contact.html" class="but" title="">Contact us</a></div>
                    </div>
                    <div id="logo">PIBAS FedSPARQL
                        <h2><a href="http://cpctas-lcmb.pmf.kg.ac.rs" id="metamorph" target="_blank">Design by CPCTAS team</a></h2>
                    </div>
                </div>
                <!-- header ends -->
                <!-- content begins -->

                <div id="content">
                    <div id="content_top">
                        <div id="content_bot">
                            <div id="right">
                                <div class="tit_bot">
                                    <h1>PIBAS FedSPARQL - user guide</h1>
                                    <br/>
                                    <div style="text-align: justify;">
                                        <p>PIBAS FedSPARQL, an open-source SPARQL query builder and result set visualizer for bioinformatics data, which allows end-users to easily construct and run Federated SPARQL queries across multiple datasets.</p>
                                        <br/>


                                        <p><b>Basic steps:</b><br/> 
                                            <ol LINE-HEIGHT:20px>
                                                <li>Select topic</li>
                                                <li>Select subtopic</li>
                                                <li>Select template</li>
                                                <li>Enter keyword</li>
                                                <li>Run query</li>

                                            </ol>
                                            <br/>
                                            <div class="pic">
                                                <table style="border-style: double;" cellspacing="10px;">
                                                    <tr><td>Example of running template <i>Find targets for the drug</i></td></tr>												
                                                    <tr><td><img style="cursor: pointer;" id="myImg1" src="./images/userguide/basicSteps.jpg" alt='Basic Step' width="600px;" onclick="showImage(this)"/>
                                                        </td></tr>

                                                </table>
                                            </div>
                                            <br/>
                                            <p><b>Additional features</b><br/><br/>

                                                <p style="text-align: justify;"><b>1. Add new dataset</b>: Users need to enter information such as: dataset name, initiative name, dataset link, comment, endpoint URL, pattern query and some dataset properties that are most important for the selected template and topic. Additional properties would be used for the feature of similar data items detection. Requirement pattern query should match to a selected topic and template. Conversance with SPARQL is necessary for this step, because the added dataset may be unfamiliar to bioinformatics community. Following our use case, pattern query must contain variable target that fits to the name of running template. Pattern query variable is visible in the right corner of the pop-up window for adding of new dataset.</p><br/>
                                                <div class="pic">
                                                    <table style="border-style: double;" cellspacing="10px;">
                                                        <tr><td>Example of adding test dataset</td></tr>													 
                                                        <tr><td><img style="cursor: pointer;" id="myImg2" src="./images/userguide/addNewDataset.jpg" alt='Adding of new dataset' width="600px;" onclick="showImage(this)"/></td></tr>                                                   
                                                        <tr><td>Result after adding newdataset</td></tr>												
                                                        <tr><td><img style="cursor: pointer;" id="myImg3" src="./images/userguide/resultAfterAddingNewDataset.jpg" alt='Result of adding new dataset' width="600px;" onclick="showImage(this)"/></td></tr>
                                                    </table>
                                                </div>
                                                <br/><br/>
                                                <p style="text-align: justify;"><b>2. Dynamic query filtering</b>: This feature allows them to improve their queries using the underlying structure of datasets without the prior knowledge of their structure.Each dataset used in query is assigned to accordion element. Accordion elements are labeled with dataset name and initiative name. The names are linked, so the end-user can directly explore the given dataset or initiative through their websites or public endpoints. By clicking on accordion element, it gets expanded and automatically populated with the list of properties, which are dependent on selected template and topic. This list is generated by running background dynamic SPARQL query. Each property, listed in accordion element, has a hyperlink to the web page with its description. Analyzing properties, end-users can decide whether some of them are relevant for obtaining additional information. Each property can be added to the query, by selecting it, and then, the query button "Run query" changes to "Run new query".</p><br/>
                                                <div class="pic">
                                                    <table style="border-style: double;" cellspacing="10px;">
                                                        <tr><td>Accordion elements for dynamic query filtering</td></tr>
                                                        <tr><td><img style="cursor: pointer;" id="myImg4" src="./images/userguide/accordionElement.jpg" alt='Filter query' width="600px;" onclick="showImage(this)"/></td></tr>
                                                        <tr><td>A sample result table after dynamic query filter</td></tr>
                                                        <tr><td><img style="cursor: pointer;" id="myImg5" src="./images/userguide/resultAfterDynamicQueryFiltering.jpg" alt='Result of query filtering' width="600px;" onclick="showImage(this)"/></td></tr>
                                                    </table>
                                                </div>
                                                <br/><br/>
                                                <p style="text-align: justify;"><b>3. Detection of similar data items</b>:This option can be applied on the results of predefined queries as well as on the results retrieved after adding a new dataset. This feature could be disabled for some templates in DataSources ontology. Based on the input of RC staff, this option is important for Biology and Chemogenomic topics. Following the use case, after adding the test dataset, researchers can find the most similar targets by click on button "Detect similar data items". This can be useful for them because known targets can then be used to make sense of novel targets.</p><br/>
                                                <div class="pic">
                                                    <table style="border-style: double;" cellspacing="10px;">
                                                        <tr><td>Similar data items after adding of new dataset</td></tr>												
                                                        <tr><td><img style="cursor: pointer;" id="myImg1" src="./images/userguide/similarItems.jpg" alt='Simialr data items' width="600px;" onclick="showImage(this)"/>
                                                            </td></tr>

                                                    </table>
                                                </div>


                                                <!-- The Modal -->
                                                <div id="myModal" class="modal">

                                                    <!-- The Close Button -->
                                                    <span class="close" onclick="document.getElementById('myModal').style.display = 'none'">&times;</span>

                                                    <!-- Modal Content (The Image) -->
                                                    <img class="modal-content" id="img01">

                                                        <!-- Modal Caption (Image Text) -->
                                                        <div id="caption"></div>
                                                </div>                       

                                            </p>


                                    </div>
                                </div><br />

                            </div>  
                            <div id="left" style="text-align: justify;">
                                <h2>News</h2>
                                <div class="fb-page" data-href="https://www.facebook.com/CPCTAS-LCMB-729830987115335/?pnref=lhc" data-height="3000" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" data-show-posts="true"></div>
                            </div>
                            <br />
                            <div style="clear: both"></div>
                            <div class="bot"></div>

                        </div>
                    </div> 


                </div>
            </div>
            <!-- content ends -->
            <!-- footer begins -->
            <div id="footer">
                Copyright  2016. Designed by <a href="http://cpctas-lcmb.pmf.kg.ac.rs" title="CPCTAS" target="_blank">CPCTAS team</a>	
                <br/>
                <a href="http://imi.pmf.kg.ac.rs/" target='_blank'>IMI</a> | <a href="http://www.pmf.kg.ac.rs/" target='_blank'>PMF</a> | <a href="http://www.kg.ac.rs/" target='_blank'>University of Kragujevac</a>

            </div>
        </div>
        </div>
    </body>
</html>
