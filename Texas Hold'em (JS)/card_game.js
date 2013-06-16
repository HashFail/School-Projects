//Jake Christensen
function Card(nSuit, nRank)
{	
	this.src = "cardimages/" + nRank + nSuit + ".png";
	if(nRank == 1)
		nRank = 14;
	//added ace exception handler since my game has aces as the highest rank. 
	this.rank = nRank; 
	this.suit = nSuit;
}

function buildDeck()
{
	var deck = new Array();
	for(var i=0, rank=1; i<52; i++, rank+=.25)
	{
		deck.push(new Card((i%4+1), Math.floor(rank)));
	}
	return deck;
}

players = new Array();
function Player(nName, nMoney)
{
	this.cards = new Array();
	this.name = nName; 
	this.money = nMoney;
	this.active = true; 
	this.bet = 0;
	this.hand = null;
	this.raised = false; //hand, bet, active and raised were added because the game called for such measures, they were not part of the original engine. 
	players.push(this);
	if(players.length>1)
		players[players.length-2].nextPlayer = this;
}

function shuffle(deck)
{
	var tempDeck = new Array();
	while(deck.length>0)
	{
		var cardN = Math.floor(Math.random()*1000000)%deck.length; 
		var card = deck[cardN];
		deck.splice(cardN, 1);
		tempDeck.push(card);
	}
	return tempDeck;
}

function drawCard(deck)
{
	card = deck[0];
	deck.splice(0, 1);
	return card; 
}

function dealCards(deck, cards)
{
	for(var i = 0; i<cards; i++)
	{
		for(var x = 0; x<players.length; x++)
		{
			players[x].cards.push(drawCard(deck));
		}
	}
}