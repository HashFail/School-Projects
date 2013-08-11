Array.prototype.rSort = function()
{
	var array = new Array(this[0]);
	var tempTL = this.length;
	this.splice(0, 1);
	for(var i = 0; array.length < tempTL; i++)
	{
		var tempL = array.length;
		for(var i=0; array.length == tempL; i++)
		{
			if(this[0].rank<array[i].rank)
				{array.splice(i, 0, this[0]); this.splice(0,1);}
			else if(this[0].rank == array[i].rank)
			{
				if(this[0].suit<array[i].suit)
					{array.splice(i,0, this[0]); this.splice(0,1);}
			}
			if(i+1 == array.length && array.length == tempL)
				{array.push(this[0]); this.splice(0,1);}
		}
	}
	return array;
}
function determineHands(start)
{
	if(start==0)
		var end = 1;
	else
		var end = players.length;
	for(var i = start; i<end; i++)
	{
		var tempCards = players[i].cards.concat(pot.cards).rSort();
		if(players[i].active)
		{
			var flush = checkFlush(tempCards);
			if(flush>0)
			{
				var straight = checkStraight(tempCards);
				if(straight[0])
					if(straight[1].rank == 14)
						players[i].hand = new Array(10, flush);
					else
						players[i].hand = new Array(9, (flush + straight[1]/10));
				else
				{
					players[i].hand = new Array(6, flush, tempCards[tempCards.length-1].rank, tempCards[tempCards.length-2].rank, tempCards[tempCards.length-3].rank, tempCards[tempCards.length-4].rank,tempCards[tempCards.length-5].rank);
				}
			}
			else
			{
				straight = checkStraight(tempCards);
				if(straight[0])
				{
					players[i].hand = new Array(5,(straight[1]+straight[2]/10), straight[3], straight[4], straight[5], straight[6]);
				}
				else
				{
					var matches = checkMatch(tempCards);
					if(typeof matches[0] === "undefined")
					{
						players[i].hand = new Array();
						players[i].hand[0] = 1;
						players[i].hand = players[i].hand.concat(getTopCards(5,players[i].cards.concat(pot.cards).rSort()));
					}
					else if(matches[0]<4)
					{
						var pos3 = matches.indexOf(3)
						if(pos3>-1)
						{
							if(pos3 != matches.lastIndexOf(3) && matches.lastIndexOf(3)>-1)
								players[i].hand = new Array(7, rankIt(matches[pos3+1], matches[pos3+2], matches[pos3+3]), rankIt(matches[matches.lastIndexOf(3)+1], matches[matches.lastIndexOf(3)+2]));
							else if(matches.indexOf(2)>-1)
								players[i].hand = new Array(7, rankIt(matches[pos3+1], matches[pos3+2], matches[pos3+3]), rankIt(matches[matches.indexOf(2)+1], matches[matches.indexOf(2)+2]));
							else
								players[i].hand = new Array(4, rankIt(matches[pos3+1], matches[pos3+2], matches[pos3+3])).concat(getTopCards(2,players[i].cards.concat(pot.cards).rSort(),matches[pos3+1].rank));
						}
						else if(matches.indexOf(2) != matches.lastIndexOf(2))
							players[i].hand = new Array(3, rankIt(matches[matches.indexOf(2)+1], matches[matches.indexOf(2)+2]), rankIt(matches[matches.lastIndexOf(2)+1], matches[matches.lastIndexOf(2)+2])).concat(getTopCards(1,players[i].cards.concat(pot.cards).rSort(),matches[matches.lastIndexOf(2)+1].rank,matches[matches.indexOf(2)+1].rank));
						else
							players[i].hand = new Array(2, rankIt(matches[matches.indexOf(2)+1], matches[matches.indexOf(2)+2])).concat(getTopCards(3,players[i].cards.concat(pot.cards).rSort(), matches[matches.indexOf(2)+1].rank));
					}
					else
					{
						players[i].hand = new Array(8, matches[1].rank).concat(getTopCards(1,players[i].cards.concat(pot.cards).rSort(), matches[1].rank));
					}
				}
			}
		}
	}
}
function rankIt()
{
	var rank = arguments[0].rank; 
	for(var i = 0; i<arguments.length; i++)
	{
		var dividen = 10;
		for(var x = 1; x<i+1; x++)
		{
			dividen = dividen*10; 
		}
		var quotient = arguments[i].suit/dividen; 
		rank += quotient;
		rank = parseFloat(rank.toFixed(i+1));
	}
	return rank; 
}
function getTopCards(num, cards)
{
	if(!(typeof arguments[2] === "undefined"))
	{
		for(var i = 2; i<arguments.length; i++)
		{
			while(cards.rSearch(arguments[i])>-1)
			{
				cards.splice(cards.rSearch(arguments[i]),1)
			}
		}
	}
	if(cards.length < num)
		alert(cards.length + ", " + num);
	var topCards = new Array();
	for(var i = 1; topCards.length<num; i++)
	{
		topCards.push(cards[cards.length - i].rank + (cards[cards.length - i].suit/10));
	}
	return topCards;
}
function checkMatch(cards)
{
	var matches = new Array();
	var num; 
	while(cards.length>0)
	{
		num = null;
		if(cards.length == 1)
			return matches;
		if(cards[cards.length-1].rank == cards[cards.length-2].rank)
		{
			if(cards.length == 2)
			{
				matches.push(2, cards[cards.length-1], cards[cards.length-2]);
				return matches;
			}
			if(cards[cards.length-1].rank==cards[cards.length-3].rank)
			{
				if(cards.length == 3)
				{
					matches.push(3, cards[cards.length-1], cards[cards.length-2], cards[cards.length-3]);
					return matches;
				}
				if(cards[cards.length-1].rank==cards[cards.length-4].rank)
				{
					return new Array(4, cards[cards.length-1], cards[cards.length-2], cards[cards.length-3], cards[cards.length-4]);
				}
				else
				{
					num = 3;
					matches.push(3, cards[cards.length-1], cards[cards.length-2], cards[cards.length-3]);
				}
			}
			else
			{
				num = 2;
				matches.push(2, cards[cards.length-1], cards[cards.length-2]);
			}
		}
		if(num == null)
			cards.splice(cards.length-1, 1);
		else
		{
			for(var y = 0; y<num; y++)
			{
				cards.splice(cards.length - 1, num);
			}
		}
	}
}
function checkFlush(cards)
{
	var clubs = 0, diamonds = 0, hearts = 0; spades = 0, flush = 0;
	for(var i = 0; i<cards.length; i++)
	{
		if(cards[i].suit == 1)clubs++;
		if(cards[i].suit == 2)diamonds++;
		if(cards[i].suit == 3)hearts++;
		if(cards[i].suit == 4)spades++;
	}
	if(clubs>4)
	{
		var flush = 1; 
	}
	if(diamonds>4)
	{
		var flush = 2;
	}
	if(hearts>4)
	{
		var	flush = 3; 
	}
	if(spades>4)
	{
		var flush = 4;
	}
	if(flush>0)
	{
		for(var i = 0; i<cards.length; i++)
		{
			if(cards[i].suit != flush)
				cards.splice(i,1);
		}
	}
	return flush;
}
function checkStraight(cards)
{
	var end = cards[cards.length-1].rank;
	var tempEnd = end;
	var i = 0;
	while(end>-1)
	{
		if(cards.rSearch(end)>-1)
		{
			i++;
			if(i==5)
				return new Array(true, tempEnd, cards[cards.rSearch(tempEnd)].suit, cards[cards.rSearch(tempEnd-1)].suit, cards[cards.rSearch(tempEnd-2)].suit, cards[cards.rSearch(tempEnd-3)].suit, cards[cards.rSearch(tempEnd-4)].suit);
		}
		else
		{
			i = 0; 
			tempEnd = end-1;
			if(end == 3)
			{
				return new Array(false);
			}
		}
		end--;
	}
	return new Array(false);
}
Array.prototype.rSearch = function(num)
{
	for(var i = this.length - 1; i > -1; i--)
	{
		if(this[i].rank == num)
		{
				return i; 
		}
	}
	return -1;
}

function determineWinner()
{
	for(var i = 0; i<players.length; i++)
	{
		if(players[i].active)
		{
			var winner = new Array();
			winner.push(i);
			break;
		}
	}
	for(var i = winner[0]+1; i<players.length; i++)
	{
		if(players[i].active)
		{	
			var twinner = winner[0]; 
			for(var x = 0; x<players[twinner].hand.length; x++)
			{
				if(players[twinner].hand[x] > players[i].hand[x])
					break;
				else if(players[twinner].hand[x] < players[i].hand[x])
				{
					winner = new Array();
					winner.push(i);
					break;
				}
				if(i == players[winner].hand.length-1)
				{
					winner.push(i);
					break;
				}
			}
		} 
	}
	return winner;
}