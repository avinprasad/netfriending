			<?php
			if ($posts < 10) {$level = "Newbie"; $starno = 1;}
			elseif ($posts >= 10 && $posts < 50) {$level = "New PPL"; $starno = 2;}
			elseif ($posts >= 50 && $posts < 100) {$level = "Non Spammer"; $starno = 3;}
			elseif ($posts >= 100 && $posts < 200) {$level = "Forum Regular"; $starno = 4;}
			elseif ($posts >= 200 && $posts < 350) {$level = "Poster"; $starno = 5;}
			elseif ($posts >= 350 && $posts < 500) {$level = "Forum Poster"; $starno = 6;}
			elseif ($posts >= 500 && $posts < 700) {$level = "Frequent Poster"; $starno = 7;}
			elseif ($posts >= 700 && $posts < 1000) {$level = "Talented Poster"; $starno = 8;}
			elseif ($posts >= 1000 && $posts < 1500) {$level = "Top Poster"; $starno = 9;}
			elseif ($posts >= 1500 && $posts < 2000) {$level = "Super Poster"; $starno = 10;}
			elseif ($posts >= 2000 && $posts < 3000) {$level = "Off-Scale Poster"; $starno = 11;}
			elseif ($posts >= 3000 && $posts < 4000) {$level = "Forum Guide"; $starno = 12;}
			elseif ($posts >= 4000 && $posts < 5000) {$level = "Forum President"; $starno = 13;}
			elseif ($posts >= 5000 && $posts < 10000) {$level = "NetFriending Recommended"; $starno = 14;}
			elseif ($posts >= 10000) {$level = "NetFriending Lover"; $starno = 15;}
			?>