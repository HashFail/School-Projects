function Pot(nName, nMoney)
{
	var player = new Player(nName, nMoney);
	players.splice(players.length-1, 1);
	players[players.length-1].nextPlayer = players[0];
	return player; 
}
function antee()
{
	comp.money-=2;
	human.money-=2;
	house.money-=2;
	pot.money+=6; 
	display(4);
}
function dealPotCard(check)
{
	pot.cards.push(drawCard(curDeck));
	display(2);
	if(check)
		determineHands(1);
}
function dealNextCard()
{				
	var folded=0;
	for(var i = 0; i<players.length; i++)
	{
		if(!players[i].active)
			folded++;
		else
		{
			var winner = new Array();
			winner.push(i);
		}
	}
	if(folded == 2)
	{
		endHand(winner);
	}
	else if(pot.cards.length == 5)
	{
		//decide winner
		determineHands(0);
		display(3);
		var winner = determineWinner();
		endHand(winner);
	}
	else
	{
		for(var i = 0; i<players.length; i++)
		{
			players[i].bet = 0; 
			players[i].raised = false; 
		}
		curBet.placer = null; 
		curBet.value = 0; 
		dealPotCard(true);
		if(human.active)
			commandDivs[0].style.display = "block";
		else
			setTimeout(function(){makeMove(comp);},3500);
	}
}
function endHand(winner)
{
	for(var i = 0; i<players.length; i++)
	{
		players[i].bet = 0; 
		players[i].raised = false; 
		players[i].active = true;
		document.getElementById(players[i].name + "Span").style.opacity = "1";
	}
	curBet.placer = null; 
	curBet.value = 0; 
	pot.money = pot.money/winner.length;
	var names = "";
	for(var i =0; i<winner.length; i++)
	{
		players[winner[i]].money += pot.money;
		names += players[winner[i]].name + " ";
	}
	if(winner.length == 1)
	{
		messageBoxVar.innerHTML = "The " + names + " has won the hand and collected $" + pot.money;
	}
	else 
	{
		messageBoxVar.innerHTML = "Tie between " + names;
	}
	pot.money = 0;
	display(4);
	pot.cards = new Array();
	human.cards = new Array();
	comp.cards = new Array();
	house.cards = new Array();
	setTimeout(function(){startButtonVar.style.display = "inline"; gameField.style.opacity = ".2";},7000);
}
function dealCardsMod(deck, cards)
{
	dealCards(deck, cards);
	dealPotCard(false);
	dealPotCard(false);
	dealPotCard(true);
}
function startHand()
{	
	startButtonVar.style.display = "none";
	gameFieldVar.style.opacity = "1";
	for(var i = 0; i<pot_images.length; i++)
	{
		pot_images[i].style.display = "none";
	}
	curDeck = shuffle(buildDeck());
	antee();
	dealCardsMod(curDeck, 2);
	display(1);
	commandDivs[0].style.display = "block";
	switch(parseInt(Math.random()*1000000)%2)
	{
		case 0:
			messageBoxVar.innerHTML = "All your monies will be belonging to me!";
			break;
		case 1:
			messageBoxVar.innerHTML = "If you run out of money, your friendly neighborhood loanshark is always happy to help.";
			break;
	}
}
function display(e)
{
	if(e==1)
	{
		for(var i=0;i<human_images.length; i++)
		{
			human_images[i].src = human.cards[i].src;
			comp_images[i].src = "cardimages/back.png";
			house_images[i].src = "cardimages/back.png";
		}
	}
	if(e==2)
	{
		for(var i = 0; i<pot.cards.length; i++)
		{
			pot_images[i].style.display = "inline";
			pot_images[i].src = pot.cards[i].src;
		}
	}
	if(e==3)
	{
		for(var i=0;i<comp_images.length; i++)
		{
			if(comp.active)comp_images[i].src = comp.cards[i].src;
			if(house.active)house_images[i].src = house.cards[i].src;
		}
	}
	if(e==4)
	{
		moneySpans[0].innerHTML = "$" + pot.money; 
		moneySpans[1].innerHTML = "$" + human.money; 
		moneySpans[2].innerHTML = "$" + comp.money; 
		moneySpans[3].innerHTML = "$" + house.money; 
	}
}
function playerMove(response)
{
	switch(response)
	{
		case 1:
			commandDivs[0].style.display = "none";
			commandDivs[2].style.display = "block";
			break;
		case 2:
			commandDivs[0].style.display = "none";
			commandDivs[2].style.display = "none";
			pass(human);
			if(checkRoundOver())
				setTimeout(function(){dealNextCard();},3500);
			else
				setTimeout(function(){makeMove(comp);},3500);
			break;
		case 3:
			commandDivs[1].style.display = "none";
			seeBet(human);
			if(checkRoundOver())
				setTimeout(function(){dealNextCard();},3500);
			else
				setTimeout(function(){makeMove(comp);},3500);
			break;
		case 5:
			commandDivs[1].style.display = "none";
			fold(human);
			if(checkRoundOver())
				setTimeout(function(){dealNextCard();},3500);
			else
				setTimeout(function(){makeMove(comp);},3500);
			break;
	}
}
function pass(player)
{
	player.raised = true;
	messageBoxVar.innerHTML = "The "+player.name+" has passed.";
}
function seeBet(player)
{
	if(player.money < curBet.value - player.bet)
	{
		alert("folding");
		if(player.name == "human")
		{
			messageBoxVar.innerHTML = "You do not have enough money, you must fold";
			
		}
		fold(player);
		return;
	}
	pot.money += (curBet.value - player.bet);
	player.money -= (curBet.value - player.bet);
	player.bet = curBet.value;
	display(4);
	messageBoxVar.innerHTML = "The "+player.name+" will see the "+curBet.placer.name+"'s bet.";
	player.raised = true;
}
function placeBet(amount, player)
{
	if(amount > player.money)
	{
		if(player.name = "human")
		{
			messageBoxVar.innerHTML = "You do not have enough money.";
			return;
		}
		else
		{
			amount = player.money;
		}
	}
	else if(amount != parseInt(amount))
	{
		messageBoxVar.innerHTML = "Whole numbers only. We are not childern here. We do not bet with cents.";
		return;
	}
	if(isNaN(amount))
	{
		messageBoxVar.innerHTML = "Enter a number asshole.";
		return;
	}
	player.bet = amount;
	curBet.value = amount;
	curBet.placer = player;
	player.money = player.money - amount;
	pot.money = pot.money + amount;
	player.raised = true; 
	display(4);
	commandDivs[2].style.display = "none";
	messageBox.innerHTML = "The " + player.name + " has placed a bet of $" + amount+".";
	if(player.nextPlayer == human)
	{
	if(human.active)
		commandDivs[1].style.display = "block";
	else
		setTimeout(function(){makeMove(comp);},3500)
	}
	else if(player == human && !checkRoundOver())
	{
		setTimeout(function(){makeMove(player.nextPlayer);},3500);
	}
}
function makeMove(player)
{
	if(player.active)
	{
		choice = parseInt(Math.random()*1000000)%20+1;
		if(curBet.value>player.bet)
		{
			if(choice>10)
			{
				//make smart move
				if(player.hand[0]>1)
				{
					if(player.hand[0]>=3)
						raise(player, Math.ceil((choice-10)/2));
					else
						seeBet(player);
				}
				else
				{
					choice = parseInt(Math.random()*1000000)%2;
					if(choice==1)
						fold(player);
					else
						seeBet(player);
				}
			}
			else if(choice>8)
			{
				//fold
				fold(player);
			}
			else if(choice>3)
			{
				//see
				seeBet(player);
			}
			else
			{
				raise(player, choice);
			}
		}else if(!player.raised){
			if(choice>10)
			{
				if(player.hand[0]>1)
				{
					if(player.hand[0]>=3)
					{
						placeBet(Math.ceil((choice)/2), player);
					}
					else
						placeBet(Math.ceil((choice-10)/2), player);
				}
				else
					pass(player);
			}
			else if(choice > 5)
				pass(player);
			else
				placeBet(choice, player);
		}
		if(checkRoundOver())
			setTimeout(function(){dealNextCard();},3500);
		else if(player.nextPlayer != human && player.nextPlayer.active)
			setTimeout(function(){makeMove(player.nextPlayer);},3500);
		else if(human.active)
			commandDivs[1].style.display = "block";
	}
	else
	{
		if(checkRoundOver())
			setTimeout(function(){dealNextCard();},3500);
		else if(player.nextPlayer != human && player.nextPlayer.active)
			setTimeout(function(){makeMove(player.nextPlayer);},10);
		else if(human.active)
			commandDivs[1].style.display = "block";
	}
}
function checkRoundOver()
{
	return (human.raised && human.bet == curBet.value || human.active == false) && (comp.raised && comp.bet == curBet.value || comp.active == false) && (house.raised && house.bet == curBet.value || house.active == false);
}
function fold(player)
{
	player.active = false; 
	messageBoxVar.innerHTML = "The "+player.name+" has folded.";
	document.getElementById(player.name + "Span").style.opacity = ".4"; 
}
function raise(player, choice)
{
	if(!player.raised && curBet.value + choice < player.money)
	{
		//raise
		curBet.value += choice; 
		player.bet = curBet.value;
		pot.money += player.bet;
		player.money -= player.bet; 
		player.raised = true;
		display(4);
		messageBoxVar.innerHTML = "The "+player.name+" has raised the "+curBet.placer.name+" $" + choice + ".";
		curBet.placer = player;
		if(player.nextPlayer == human)
		{
			messageBoxVar.innerHTML += " Will you see the raise?"
			commandDivs[1].style.display = "block";
		}
	}else{
		seeBet(player);
	}
}