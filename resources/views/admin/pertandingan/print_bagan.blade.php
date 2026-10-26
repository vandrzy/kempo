       @php
            $undian = [];
            // dd($kolom1);
            foreach ($kolom1 as $item) {
                $undian[] = ($item->undian + 1).($item->kabupaten_id == null ? " | Bye" : '') ;
            }

            // $undian2 = [];
            // // dd($kolom1);
            // foreach ($kolom2 as $item) {
            //     $undian2[] = ($item->undian + 1).($item->kabupaten_id == null ? " | Bye" : '') ;
            // }
            // dd($undian);
        @endphp

<!DOCTYPE html>
<html>
	<head>
		<title>{{ 'Bagan_Pertandingan_Babak_'.$_GET['print'].'_'.date('Y-m-d').'.pdf' }}</title>
		<script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.13.6/underscore-min.js"></script>
		<script>
			$(document).on('ready', function() {

				var knownBrackets = [2,4,8,16,32], // brackets with "perfect" proportions (full fields, no byes)
                    exampleTeams1 = @json($undian);

                    // console.log(exampleTeams);

					// exampleTeams  = _.shuffle(
                    //     ["New Jersey Devils",
                    //     "New York Islanders",
                    //     "New York Rangers",
                    //     "Philadelphia Flyers",
                    //     "Pittsburgh Penguins",
                    //     "Boston Bruins",
                    //     "Buffalo Sabres",
                    //     "Montreal Canadiens",
                    //     "Ottawa Senators",
                    //     "Toronto Maple Leafs",
                    //     "Carolina Hurricanes",
                    //     "Florida Panthers",
                    //     "Tampa Bay Lightning",
                    //     "Washington Capitals",
                    //     "Winnipeg Jets","Chicago Blackhawks","Columbus Blue Jackets","Detroit Red Wings","Nashville Predators","St. Louis Blues","Calgary Flames","Colorado Avalanche","Edmonton Oilers","Minnesota Wild","Vancouver Canucks","Anaheim Ducks","Dallas Stars","Los Angeles Kings","Phoenix Coyotes","San Jose Sharks","Montreal Wanderers","Quebec Nordiques","Hartford Whalers"]), // because a bracket needs some teams!

		// exampleTeams  = ["New Jersey Devils",
        //                 "New York Islanders",
        //                 "New York Rangers",
        //                 "Philadelphia Flyers",
        //                 "Pittsburgh Penguins",
        //                 "Boston Bruins",
        //                 "Buffalo Sabres",
        //                 "Montreal Canadiens",
        //                 "Ottawa Senators",
        //                 "Toronto Maple Leafs",
        //                 "Carolina Hurricanes",
        //                 "Florida Panthers",
        //                 "Tampa Bay Lightning",
        //                 "Washington Capitals",
        //                 "Winnipeg Jets","Chicago Blackhawks","Columbus Blue Jackets","Detroit Red Wings","Nashville Predators","St. Louis Blues","Calgary Flames","Colorado Avalanche","Edmonton Oilers","Minnesota Wild","Vancouver Canucks","Anaheim Ducks","Dallas Stars","Los Angeles Kings","Phoenix Coyotes","San Jose Sharks","Montreal Wanderers","Quebec Nordiques","Hartford Whalers"], // because a bracket needs some teams!


					bracketCount = 0;

				/*
				 * Build our bracket "model"
				 */
				function getBracket(base ,id_brackets, exampleTeams) {

					var closest 		= _.find(knownBrackets, function(k) { return k>=base; }),
						byes 			= closest-base;

					if(byes>0)	base = closest;

					var brackets 	= [],
						round 		= 1,
						baseT 		= base/2,
						baseC 		= base/2,
						teamMark	= 0,
						nextInc		= base/2;

					for(i=1;i<=(base-1);i++) {
						var	baseR = i/baseT,
							isBye = false;

						if(byes>0 && (i%2!=0 || byes>=(baseT-i))) {
							isBye = true;
							byes--;
						}

						var last = _.map(_.filter(brackets, function(b) { return b.nextGame == i; }), function(b) { return {game:b.bracketNo,teams:b.teamnames}; });

						brackets.push({
							lastGames:	round==1 ? null : [last[0].game,last[1].game],
							nextGame:	nextInc+i>base-1?null:nextInc+i,
							teamnames:	round==1 ? [exampleTeams[teamMark],exampleTeams[teamMark+1]] : [last[0].teams[_.random(1)],last[1].teams[_.random(1)]],
							bracketNo:	i,
							roundNo:	round,
							bye:		isBye
						});
						teamMark+=2;
						if(i%2!=0)	nextInc--;
						while(baseR>=1) {
							round++;
							baseC/= 2;
							baseT = baseT + baseC;
							baseR = i/baseT;
						}
					}

					renderBrackets(brackets, id_brackets);
				}

				/*
				 * Inject our brackets
				 */
				function renderBrackets(struct, id_brackets) {
					var groupCount	= _.uniq(_.map(struct, function(s) { return s.roundNo; })).length;



					var group	= $('<div class="group'+(groupCount+1)+'" id="b'+bracketCount+'"></div>'),
						grouped = _.groupBy(struct, function(s) { return s.roundNo; });
                    console.log(grouped);
                    console.log(groupCount);

                    var list = [];

                    if(groupCount == 4) {
                        list = ['Penyisihan I', 'Penyisihan II', 'Semi Final', 'Final'];
                    } else {
                        list = ['Penyisihan', 'Semi Final', 'Final'];

                    }

					for(g=1;g<=groupCount;g++) {
						var round = $('<div class="r'+g+'"><h4 style="text-align:center;">'+list[g - 1]+'</h4></div>');
						_.each(grouped[g], function(gg, i) {
							if(gg.bye)
								round.append('');
							else
                                if(groupCount == 4) {
                                    if(g == 1) {
                                        // <span class="info">'+gg.bracketNo+'</span>
                                        round.append('<div><div class="bracketbox"><span class="teama">'+grouped[g][0].teamnames[0]+'</span><span class="teamb">'+grouped[g][0].teamnames[1]+'</span></div></div>');
                                        console.log(g);

                                    } else {

                                        if(g == 2) {
                                            if(i == 0) {
                                                round.append('<div><div class="bracketbox"><span class="teama"></span><span class="teamb">'+grouped[g - 1][i + 1].teamnames[0]+'</span></div></div>');
                                            } else {
                                                round.append('<div><div class="bracketbox"><span class="teama">'+grouped[g - 1][i].teamnames[1]+'</span><span class="teamb">'+grouped[g - 1][i + 1].teamnames[0]+'</span></div></div>');
                                            }
                                            console.log(g);

                                        } else {
                                            round.append('<div><div class="bracketbox"><span class="teama"></span><span class="teamb"></span></div></div>');

                                        }
                                    }
                                } else {
                                    if(g == 1) {
                                        round.append('<div><div class="bracketbox"><span class="info">'+gg.bracketNo+'</span><span class="teama">'+gg.teamnames[0]+'</span><span class="teamb">'+gg.teamnames[1]+'</span></div></div>');
                                    } else {
                                        round.append('<div><div class="bracketbox"><span class="info">'+gg.bracketNo+'</span><span class="teama"></span><span class="teamb"></span></div></div>');
                                    }
                                }

                                    // round.append('<div><div class="bracketbox"><span class="info">'+gg.bracketNo+'</span><span class="teama">'+gg.teamnames[0]+'</span><span class="teamb">'+gg.teamnames[1]+'</span></div></div>');

						});
						group.append(round);
					}
					// group.append('<div class="r'+(groupCount+1)+'"><div class="final"><div class="bracketbox"><span class="teamc">'+_.last(struct).teamnames[_.random(1)]+'</span></div></div></div>');
					group.append('<div class="r'+(groupCount+1)+'"><div class="final"><div class="bracketbox"><span class="teamc"></span></div></div></div>');

					$('#' + id_brackets).append(group);

					bracketCount++;
					$('html,body').animate({
						scrollTop: $("#b"+(bracketCount-1)).offset().top
					});
				}



                var opts = parseInt(exampleTeams1.length);

					if(!_.isNaN(opts) && opts <= _.last(knownBrackets))
						getBracket(opts, 'brackets_1', exampleTeams1);
					else
						alert('The bracket size you specified is not currently supported.');


            //    var opts = parseInt(exampleTeams2.length);

			// 		if(!_.isNaN(opts) && opts <= _.last(knownBrackets))
			// 			getBracket(opts, 'brackets_1', exampleTeams2);
			// 		else
			// 			alert('The bracket size you specified is not currently supported.');
				$('#add').on('click', function() {
					var opts = parseInt(prompt('Bracket size (number of teams):',32));

					if(!_.isNaN(opts) && opts <= _.last(knownBrackets))
						getBracket(opts);
					else
						alert('The bracket size you specified is not currently supported.');
				});

			});
		</script>
        {{-- <script>window.print();</script> --}}
        <style type="text/css">
			html, body, .brackets {
				width: 100%;
				min-height: 100%;
				font-family: "Arial", sans-serif;
			}

            header {
                position: fixed;
                top: -80px; /* sesuaikan agar pas */
                left: 0;
                right: 0;
                height: 50px;
                padding-bottom: 10px;
                border-bottom: 1px solid #000;
            }

            footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #ccc;
            text-align: right;
            font-size: 11px;
            padding: 8px 0;
            }
			.metroBtn {
				background-color: #2E7BCC;
				color: #fff;
				font-size: 1.1em;
				padding: 10px;
				display: inline-block;
				margin-bottom: 30px;
				cursor: pointer;
			}
			.brackets > div {
				vertical-align: top;
				clear: both;
			}
			.brackets > div > div {
				float: left;
				height: 100%;
			}
			.brackets > div > div > div {
				margin: 50px 0;
			}
			.brackets div.bracketbox {
				position: relative;
				width: 100%; height: 100%;
				border-top: 1px solid #555;
				border-right: 1px solid #555;
				border-bottom: 1px solid #555;
			}
			.brackets div.bracketbox > span.info {
				position: absolute;
				top: 25%;
				left: 25%;
				font-size: 0.8em;
				color: #BBB;
			}
			.brackets div.bracketbox > span {
				position: absolute;
				left: 5px;
				font-size: 0.85em;
			}
			.brackets div.bracketbox > span.teama {
				top: -20px;
			}
			.brackets div.bracketbox > span.teamb {
				bottom: -20px;
			}
			.brackets div.bracketbox > span.teamc {
				bottom: 1px;
			}
			.brackets > .group2 {
				height: 260px;
			}
			.brackets > .group2 > div {
				width: 49%;
			}
			.brackets > .group3 {
				height: 320px;
			}
			.brackets > .group3 > div {
				width: 32.7%;
			}
			.brackets > .group4 > div {
				width: 24.5%;
			}
			.brackets > .group5 > div {
				width: 19.6%;
			}
			.brackets > .group6 {
				height: 2000px;
			}
			.brackets > .group6 > div {
				width: 16.3%;
			}
			.brackets > div > .r1 > div {
				height: 50px;
			}
			.brackets > div > .r2 > div {
				margin: 80px 0 110px 0;
				height: 90px;
			}
			.brackets > div > .r3 > div {
				margin: 140px 0 220px 0;
				height: 180px;
			}
			.brackets > div > .r4 > div {
				margin: 250px 0 445px 0;
				height: 350px;
			}
			.brackets > div > .r5 > div {
				margin: 460px 0 0 0;
				height: 900px;
			}
			.brackets > div > .r6 > div {
				margin: 900px 0 0 0;
			}
			.brackets div.final > div.bracketbox {
				border-top: 0px;
				border-right: 0px;
				height: 0px;
			}
			.brackets > div > .r4 > div.drop {
				height: 180px;
				margin-bottom: 0px;
			}
			.brackets > div > .r5 > div.final.drop {
				margin-top: 345px;
				margin-bottom: 0px;
				height: 1px;
			}
			.brackets > div > div > div:last-of-type {
				margin-bottom: 0px;
			}
		</style>
	</head>
	<body>
		{{-- <p>Quick script to demonstrate generating tournament brackets without tables (using CSS). Let me know what you think <a href="http://twitter.com/sterlingwes">@SterlingWes</a> (bonus marks if you can help me improve my CSS).</p>
		<div id="add" class="metroBtn">Add Bracket</div> --}}

        <footer>
            <div style="padding-right:10px;">
                <center>

                    <h2>CUP</h2>
                </center>

            </div>
        </footer>
        <div class="container">
            <center>
                <h3>{{ $pertandingan->pertandingan->nama }}</h3>
                <h4>Babak {{ $_GET['print'] }}</h4>
            </center>
            {{-- <a onclick="window.print()">Print page</a> --}}
            <div class="brackets" id="brackets_1">
            </div>
            <div class="new-page"></div>
            <div class="brackets" id="brackets_1">
            </div>
        </div>
	</body>
</html>
