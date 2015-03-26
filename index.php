<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Academy Awards Database</title>
<link href="style.css" type="text/css" rel="stylesheet" >
<link href="normalize.css" type="text/css" rel="stylesheet" >
</head>

<body>
<div id="container">
    <div id="formContainer">
    <div id="innerFormContainer">
	<form>
        <h1 id="academyTitle">Academy Awards Database</h1>
        <div id="inputFormContainer">
        <input type="text" name="searchVal"> 
        <select name="searchBy">
        	<option value="">Select...</option>
  			<option value="year">Year</option>
            <option value="picture">Picture</option>
  			<option value="actor">Actor</option>
  			<option value="actress">Actress</option>
            <option value="director">Director</option>
            <option value="award">Award</option>
		</select>
		<input type="submit" name="submit" value="Submit">
        <input type="submit" name="records" value="Record Holders" id="recordButton" >
        </div>
    </form>
    </div>
    </div>
    <?php
		
        DEFINE('DB_USER', 'root'); 
        DEFINE('DB_PASSWORD', 'teamwho'); 
        DEFINE('DB_HOST', 'localhost'); 
        DEFINE('DB_NAME', 'oscars');
        
        $dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Could not connect: '
        . mysqli_connect_error()); 
		
		//Records holder information
		if(isset($_GET['records']))
		{
					
			$mostNomActor = "SELECT actor.actorName, COUNT(*)
				FROM nominated_actor, actor
				WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'M'
				GROUP BY nominated_actor.actorID
				HAVING COUNT(*) = 
					(SELECT MAX(C) FROM
						(SELECT COUNT(*) AS C
						FROM nominated_actor, actor
						WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'M'
						GROUP BY nominated_actor.actorID)C)";
                
             
             echo "<h1 class='searchText'> Record Holders </h1>";
              
             //Print actors with the most nominations  
            echo "<div id='firstResult' class='yearSearch'><h2>Actors with the Most Nominations</h2>"; 
               
            $mostNomActorSelect = @mysqli_query ($dbc, $mostNomActor);
             while($row = mysqli_fetch_array($mostNomActorSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['actorName'] . " - " . $row['COUNT(*)'] . ' nominations'. "</p>";
   		     }
			 echo "</div>";
			 
   		     //Print out actresses with the most nominations
			 
			 $mostNomActress = "SELECT actor.actorName, COUNT(*)
				FROM nominated_actor, actor
				WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'F'
				GROUP BY nominated_actor.actorID
				HAVING COUNT(*) = 
					(SELECT MAX(C) FROM
						(SELECT COUNT(*) AS C
						FROM nominated_actor, actor
						WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'F'
						GROUP BY nominated_actor.actorID)C)";
                 
            echo "<div class='yearSearch'><h2>Actress with the Most Nominations</h2>"; 
             
            $mostNomActressSelect = @mysqli_query ($dbc, $mostNomActress);
             while($row = mysqli_fetch_array($mostNomActressSelect, MYSQLI_ASSOC))
             {
               echo "<p> " . $row['actorName'] . " - " . $row['COUNT(*)'] . ' nominations'. "</p>";
   		     }
   		     
            echo "</div>";
			
            //Print actors with the most wins  
           $mostWinActor = "SELECT actor.actorName, COUNT(*)
				FROM nominated_actor, actor
				WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'M' AND nominated_actor.won = 1
				GROUP BY nominated_actor.actorID
				HAVING COUNT(*) = 
					(SELECT MAX(C) FROM
						(SELECT COUNT(*) AS C
						FROM nominated_actor, actor
						WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'M' AND nominated_actor.won = 1
						GROUP BY nominated_actor.actorID)C)";
              
              
            echo "<div class='yearSearch'><h2>Actors with the Most Wins</h2>"; 
               
            $mostWinActorSelect = @mysqli_query ($dbc, $mostWinActor);
             while($row = mysqli_fetch_array($mostWinActorSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['actorName'] . " - " . $row['COUNT(*)'] . ' wins'. "</p>";
   		     }
			 echo "</div>";
		 
   		    //Print out best actress result
   		    $mostWinActress = "SELECT actor.actorName, COUNT(*)
				FROM nominated_actor, actor
				WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'F' AND nominated_actor.won = 1
				GROUP BY nominated_actor.actorID
				HAVING COUNT(*) = 
					(SELECT MAX(C) FROM
						(SELECT COUNT(*) AS C
						FROM nominated_actor, actor
						WHERE actor.actorID = nominated_actor.actorID AND actor.gender = 'F' AND nominated_actor.won = 1
						GROUP BY nominated_actor.actorID)C)";
              
              
            echo "<div class='yearSearch'><h2>Actresses with the Most Wins</h2>"; 
               
            $mostWinActressSelect = @mysqli_query ($dbc, $mostWinActress);
             while($row = mysqli_fetch_array($mostWinActressSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['actorName'] . " - " . $row['COUNT(*)'] . ' win'. "</p>";
   		     }
			 echo "</div>";
			
		}

		//Regular database searches
		if(isset($_GET['submit']))
		{ 
		
		$searchType = $_GET['searchBy']; 
    	$searchValue = $_GET['searchVal'];
		$searchValue = ucwords($searchValue);
		
        //Search by year
        if($searchType == "year" &&$searchValue != "")
        {
            $bestPicture = "SELECT picture.title
                FROM nominated_picture INNER JOIN picture ON nominated_picture.pictureID = picture.pictureID 
                INNER JOIN award ON nominated_picture.awardID = award.awardID 
                WHERE picture.year=$searchValue AND nominated_picture.won = 1";
                
             $nomPicture = "SELECT picture.title
                FROM nominated_picture INNER JOIN picture ON nominated_picture.pictureID = picture.pictureID 
                INNER JOIN award ON nominated_picture.awardID = award.awardID 
                WHERE picture.year=$searchValue AND nominated_picture.won = 0";
                
            $bestDirector = "SELECT director.directorName, picture.title
                FROM nominated_director INNER JOIN director ON nominated_director.directorID = director.directorID 
                INNER JOIN award ON nominated_director.awardID = award.awardID 
                INNER JOIN directs ON director.directorID = directs.directorID 
                INNER JOIN picture ON picture.pictureID = directs.pictureID 
                WHERE award.year=$searchValue AND nominated_director.won = 1 AND picture.year =$searchValue";
                
            $nomDirector = "SELECT director.directorName, picture.title
                FROM nominated_director INNER JOIN director ON nominated_director.directorID = director.directorID 
                INNER JOIN award ON nominated_director.awardID = award.awardID 
                INNER JOIN directs ON director.directorID = directs.directorID 
                INNER JOIN picture ON picture.pictureID = directs.pictureID 
                WHERE award.year=$searchValue AND nominated_director.won = 0 AND picture.year =$searchValue";
                
            $bestActor = "SELECT actor.actorName, picture.title
                FROM nominated_actor INNER JOIN actor ON nominated_actor.actorID = actor.actorID 
                INNER JOIN award ON nominated_actor.awardID = award.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID 
                WHERE award.year=$searchValue AND nominated_actor.won = 1 AND actor.gender = 'M' AND picture.year =$searchValue";
            
            $nomActor = "SELECT actor.actorName, picture.title 
                FROM nominated_actor INNER JOIN actor ON nominated_actor.actorID = actor.actorID 
                INNER JOIN award ON nominated_actor.awardID = award.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID 
                WHERE award.year=$searchValue AND nominated_actor.won = 0 AND
                actor.gender = 'M'AND picture.year =$searchValue";
                
            $bestActress = "SELECT actor.actorName, picture.title  
                FROM nominated_actor INNER JOIN actor ON nominated_actor.actorID = actor.actorID 
                INNER JOIN award ON nominated_actor.awardID = award.awardID
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID 
                WHERE award.year=$searchValue AND nominated_actor.won = 1 AND
                actor.gender = 'F'AND picture.year =$searchValue"; 
                
            $nomActress = "SELECT actor.actorName, picture.title  
                FROM nominated_actor INNER JOIN actor ON nominated_actor.actorID = actor.actorID 
                INNER JOIN award ON nominated_actor.awardID = award.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID 
                WHERE award.year=$searchValue AND nominated_actor.won = 0 AND
                actor.gender = 'F'AND picture.year =$searchValue";
             
             echo "<h1 class='searchText'> Year: " . $searchValue . "</h1>";
              
             //Print out best picture result  
            echo "<div id='firstResult' class='yearSearch'><h2>Best Picture</h2>"; 
               
            $bestPictureSelect = @mysqli_query ($dbc, $bestPicture);
             while($row = mysqli_fetch_array($bestPictureSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['title'] . "</p>";
   		     }
   		     
   		      //Print out nominated pictures result 
   		      echo "<h3>Nominated</h3>"; 
   		     $nomPictureSelect = @mysqli_query ($dbc, $nomPicture);
             while($row = mysqli_fetch_array($nomPictureSelect, MYSQLI_ASSOC))
             {
                 echo "<p> " . $row['title'] . "</p>";
   		     }
			 
   		     echo "</div> "; 
			 
   		     //Print out best director result  
            echo "<div class='yearSearch'><h2>Best Director</h2>"; 
               
            $bestDirectorSelect = @mysqli_query ($dbc, $bestDirector);
             while($row = mysqli_fetch_array($bestDirectorSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['directorName'] . " - " . $row['title'] . "</p>";
   		     }
   		     
   		      //Print out nominated directors result  
            echo "<h3>Nominated</h3>"; 
               
            $nomDirectorSelect = @mysqli_query ($dbc, $nomDirector);
             while($row = mysqli_fetch_array($nomDirectorSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['directorName'] . " - " . $row['title'] . "</p>";
   		     }
			 
            echo "</div>";
			
            //Print out best actor result  
            echo "<div class='yearSearch'><h2>Best Actor</h2>"; 
               
            $bestActorSelect = @mysqli_query ($dbc, $bestActor);
             while($row = mysqli_fetch_array($bestActorSelect, MYSQLI_ASSOC))
             {
                echo "<p> " . $row['actorName'] . " - " . $row['title'] . "</p>";
   		     }
   		     
   		      //Print out nominated actors result 
   		      echo "<h3>Nominated</h3>"; 
   		     $nomActorSelect = @mysqli_query ($dbc, $nomActor);
             while($row = mysqli_fetch_array($nomActorSelect, MYSQLI_ASSOC))
             {
                 echo "<p> " . $row['actorName'] . " - " . $row['title'] . "</p>";
   		     }
			 
   		 echo "</div>";
		 
   		    //Print out best actress result
   		    echo "<div class='yearSearch'><h2>Best Actress</h2>";
            $bestActressSelect = @mysqli_query ($dbc, $bestActress); 
             while($row = mysqli_fetch_array($bestActressSelect, MYSQLI_ASSOC))
             {
                 echo "<p> " . $row['actorName'] . " - " . $row['title'] . "</p>";
   		     }
   		     
   		     //Print out nominated actresses result 
   		     echo "<h3>Nominated</h3>"; 
   		     $nomActressSelect = @mysqli_query ($dbc, $nomActress); 
             while($row = mysqli_fetch_array($nomActressSelect, MYSQLI_ASSOC))
             {
                 echo "<p> " . $row['actorName'] . " - " . $row['title'] . "</p>";
   		     }
			echo "</div>"; 
        } 
		
		
        //Search by picture
         elseif($searchType == "picture" &&$searchValue != "")
        {
            //Query for Academy Award year
            $year = "SELECT year
                FROM picture 
                WHERE picture.title='$searchValue'";
            
            //Print out year
            $yearSelect = @mysqli_query ($dbc, $year);
            $picYear = mysqli_fetch_array($yearSelect, MYSQLI_ASSOC); 
                
            echo "<h1 class='searchText'>" . $searchValue . " (" . $picYear['year']. ") " . "</h1>";
            
            //Query for if picture won or not
            $picture = "SELECT nominated_picture.won
                FROM picture LEFT JOIN nominated_picture ON picture.pictureID = nominated_picture.pictureID 
                WHERE picture.title='$searchValue'";
            
            //Print out if picture won
            echo "<div id='firstResult' class='yearSearch'><h2>Best Picture</h2>";
            $pictureSelect = @mysqli_query ($dbc, $picture);
            $picWon = mysqli_fetch_array($pictureSelect, MYSQLI_ASSOC); 
            if($picWon['won']==NULL)
                    {
                        echo "<p>Wasn't Nominated.</p>";
                    }
            elseif($picWon['won']==0)
            {
                echo "<p>Nominated</p>";
            }
            
            else
            {
                echo "<p>Won</p>";
            }
             echo "</div>";   
             
             //Query for director 
             $director = "SELECT nominated_director.won, director.directorName
                FROM nominated_director INNER JOIN director ON director.directorID = nominated_director.directorID
                INNER JOIN award ON award.awardID = nominated_director.awardID 
                INNER JOIN directs ON director.directorID = directs.directorID 
                INNER JOIN picture ON picture.pictureID = directs.pictureID
                WHERE picture.title='$searchValue' AND award.year = picture.year";
                
                
            //Print out if the director won, nominated, or wasn't nominated for best director
            echo "<div class='yearSearch'><h2>Best Director</h2>";   
            $directorSelect = @mysqli_query ($dbc, $director);
            
             if(mysqli_num_rows($directorSelect) == 0)
                        {
                            echo "<p>Wasn't Nominated</p>";
                        }
            else
            {
            while($row = mysqli_fetch_array($directorSelect, MYSQLI_ASSOC))
                {
                    
                    if($row['won']==1)
                    {
                        echo "<p>" . $row['directorName'] ."-Won </p>";
                    }
                    
                    else
                    {
                        echo "<p>" . $row['directorName'] ."-Nominated </p>";
                    }
                    
   		        }
            }
   		        echo "</div>";
   		   //Query for actor   
            $actor = "SELECT nominated_actor.won, actor.actorName
                FROM nominated_actor INNER JOIN actor ON actor.actorID = nominated_actor.actorID
                INNER JOIN award ON award.awardID = nominated_actor.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID
                WHERE picture.title='$searchValue' AND award.year = picture.year AND actor.gender='M'";
            
            echo "<div class='yearSearch'><h2>Best Actor</h2>";
            $actorSelect = @mysqli_query ($dbc, $actor);
            if(mysqli_num_rows($actorSelect) == 0)
                        {
                            echo "<p>Wasn't Nominated</p>";
                        }
            else
            {
            while($row = mysqli_fetch_array($actorSelect, MYSQLI_ASSOC))
            {
                    if($row['won']==1)
                        {
                            echo "<p>" . $row['actorName'] ."-Won </p>";
                        }
                        
                        else
                        {
                            echo "<p>" . $row['actorName'] ."-Nominated </p>";
                        }
                    
   		        }
            }
            echo "</div>";
            //Query for actress 
             $actress = "SELECT nominated_actor.won, actor.actorName
                FROM nominated_actor INNER JOIN actor ON actor.actorID = nominated_actor.actorID
                INNER JOIN award ON award.awardID = nominated_actor.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID
                WHERE picture.title='$searchValue' AND award.year = picture.year AND actor.gender='F'";
                
            echo "<div class='yearSearch'><h2>Best Actress</h2>";
            $actressSelect = @mysqli_query ($dbc, $actress);
            if(mysqli_num_rows($actressSelect) == 0)
                        {
                            echo "<p>Wasn't Nominated</p>";
                        }
            else
            {
            while($row = mysqli_fetch_array($actressSelect, MYSQLI_ASSOC))
            {
                       if($row['won']==1)
                        {
                            echo "<p>" . $row['actorName'] ."-Won </p>";
                        }
                        
                        else
                        {
                            echo "<p>" . $row['actorName'] ."-Nominated </p>";
                        }
                    
                    
   		        }
            }
			echo "</div>";
        } 
        
        //Search by actor
         elseif($searchType == "actor" &&$searchValue != "")
        {
            //Query for actor Info
            $actorInfo = "SELECT nominated_actor.won, picture.title, picture.year
                FROM nominated_actor INNER JOIN actor ON actor.actorID = nominated_actor.actorID
                INNER JOIN award ON award.awardID = nominated_actor.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID
                WHERE actor.actorName='$searchValue' AND award.year = picture.year AND actor.gender='M'";
                
            echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $actorInfoSelect = @mysqli_query ($dbc, $actorInfo);
			echo "<div class='peopleSearch'>";
            while($row = mysqli_fetch_array($actorInfoSelect, MYSQLI_ASSOC))
                {
                    if($row['won']==0)
                    {
                        echo "<p>" . $row['title'] . " (" . $row['year']. ") " . "- Nominated </p>"; 
                    }
                    
                    else
                    { 
                        echo "<p>" . $row['title'] . " (" . $row['year']. ") " . "- Won </p>";
                    }
   		        }
            echo "</div>";
        }
        
         //Search by actress
         elseif($searchType == "actress" &&$searchValue != "")
        {
            //Query for actressInfo
            $actressInfo = "SELECT nominated_actor.won, picture.title, picture.year
                FROM nominated_actor INNER JOIN actor ON actor.actorID = nominated_actor.actorID
                INNER JOIN award ON award.awardID = nominated_actor.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID
                WHERE actor.actorName='$searchValue' AND award.year = picture.year AND actor.gender='F'";
                
            echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $actressInfoSelect = @mysqli_query ($dbc, $actressInfo);
			echo "<div class='peopleSearch'>"; 
            while($row = mysqli_fetch_array($actressInfoSelect, MYSQLI_ASSOC))
                {
                    if($row['won']==0)
                    {
                        echo "<p>" . $row['title'] . " (" . $row['year']. ") " . "- Nominated </p>";
                    }
                    
                    else
                    {
                        echo "<p>" . $row['title'] . " (" . $row['year']. ") " . "- Won </p>";
                    }
   		        }
			echo "</div>";
        }
        
        //Search by director
         elseif($searchType == "director" &&$searchValue != "")
        {
            //Query for directorInfo
            $directorInfo = "SELECT nominated_director.won, picture.title, picture.year
                FROM nominated_director INNER JOIN director ON director.directorID = nominated_director.directorID
                INNER JOIN award ON award.awardID = nominated_director.awardID 
                INNER JOIN directs ON director.directorID = directs.directorID 
                INNER JOIN picture ON picture.pictureID = directs.pictureID
                WHERE director.directorName='$searchValue' AND award.year = picture.year";

                
            echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $directorInfoSelect = @mysqli_query ($dbc, $directorInfo);
            echo "<div class='peopleSearch'>";
            while($row = mysqli_fetch_array($directorInfoSelect, MYSQLI_ASSOC))
                {
                    if($row['won']==0)
                    {
                        echo "<p>" . $row['title'] . " (" . $row['year']. ") " . "- Nominated </p>";
                    }
                    
                    else
                    {
                        echo "<p>" . $row['title'] . " (" . $row['year']. ") " . "- Won </p>";
                    }
   		        }
				echo "</div>";
        }
        
        //Search by Award
         elseif($searchType == "award" &&$searchValue != "")
        {
            
           //Query for Best Actor winners
           if($searchValue=="Best Actor")
           {
             $bestActor = "SELECT actor.actorName, picture.title, picture.year
                FROM nominated_actor INNER JOIN actor ON nominated_actor.actorID = actor.actorID 
                INNER JOIN award ON nominated_actor.awardID = award.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID 
                WHERE award.category='$searchValue' AND nominated_actor.won = 1 AND actor.gender = 'M' AND award.year = picture.year";
                
              echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $bestActorSelect = @mysqli_query ($dbc, $bestActor); 
			echo "<div class='peopleSearch'>"; 
            while($row = mysqli_fetch_array($bestActorSelect, MYSQLI_ASSOC))
                {
                    echo "<p>" . $row['actorName'] . " - " . $row['title'] . " (" . $row['year']. ") " . " </p>";
   		        }
				echo "</div>";
           }
           
           //Query for Best Actress winners
           elseif($searchValue=="Best Actress")
           {
             $bestActor = "SELECT actor.actorName, picture.title, picture.year
                FROM nominated_actor INNER JOIN actor ON nominated_actor.actorID = actor.actorID 
                INNER JOIN award ON nominated_actor.awardID = award.awardID 
                INNER JOIN acts ON actor.actorID = acts.actorID 
                INNER JOIN picture ON picture.pictureID = acts.pictureID 
                WHERE award.category='$searchValue' AND nominated_actor.won = 1 AND actor.gender = 'F' AND award.year = picture.year";
                
              echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $bestActorSelect = @mysqli_query ($dbc, $bestActor); 
			echo "<div class='peopleSearch'>"; 
            while($row = mysqli_fetch_array($bestActorSelect, MYSQLI_ASSOC))
                {
                    echo "<p>" . $row['actorName'] . " - " . $row['title'] . " (" . $row['year']. ") " . " </p>";
   		        }
				echo "</div>";
           }
           
           //Query for Best Director winners
           elseif($searchValue=="Best Director")
           {
               
           
             $bestDirector = "SELECT director.directorName, picture.title, picture.year
                FROM nominated_director INNER JOIN director ON nominated_director.directorID = director.directorID 
                INNER JOIN award ON nominated_director.awardID = award.awardID 
                INNER JOIN directs ON director.directorID = directs.directorID 
                INNER JOIN picture ON picture.pictureID = directs.pictureID 
                WHERE award.category='$searchValue' AND nominated_director.won = 1 AND award.year = picture.year";
                
              echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $bestDirectorSelect = @mysqli_query ($dbc, $bestDirector);
			echo "<div class='peopleSearch'>";   
            while($row = mysqli_fetch_array($bestDirectorSelect, MYSQLI_ASSOC))
                {
                    echo "<p>" . $row['directorName'] . " - " . $row['title'] . " (" . $row['year']. ") " . " </p>";
   		        }
			echo "</div>";
           }
           
           //Query for Best Picture winners
           else
           {
               $bestPicture = "SELECT picture.title, picture.year
                FROM nominated_picture INNER JOIN picture ON nominated_picture.pictureID = picture.pictureID 
                INNER JOIN award ON nominated_picture.awardID = award.awardID 
                WHERE award.category='$searchValue' AND nominated_picture.won = 1";
                
                echo "<h1 class='peopleText'>". $searchValue ."</h1>";   
            $bestPictureSelect = @mysqli_query ($dbc, $bestPicture);
			echo "<div class='peopleSearch'>";  
            while($row = mysqli_fetch_array($bestPictureSelect, MYSQLI_ASSOC))
                {
                    echo "<p>" . $row['title'] . " (" . $row['year']. ") " . " </p>";
   		        }
			echo "</div>";
           }
        }

        else
        {
            //do nothing            
        }
		}

?>

</div>
</body>
</html>