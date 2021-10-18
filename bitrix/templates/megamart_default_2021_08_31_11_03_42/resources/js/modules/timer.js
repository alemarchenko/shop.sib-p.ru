import $ from 'jquery'
import assign from 'lodash/assign'

const Timer = ($ => {
	const Default = {
		blockClass: ".js-timer-item",
		progressClass: ".js-progress",
		progressTextClass: "js-progress-text",
	};

	class Timer
	{
		constructor(element, name, config)
		{
			this.element = element;
			this.name = name;
			this.timerInterval = false;

			this.setup(config);
			this.initialize();
		}
	}
	
	Timer.prototype.setup = function(options)
	{
		this.options = assign({}, Default, options);
		
		this.dateNow = parseInt(BX.message('SERVER_TIME'));
		this.timeLimit = this.options.DATE_TO - this.options.DATE_FROM;
		this.timeLeft = this.options.DATE_TO - this.dateNow;
		
		this.quantity = parseInt(this.options.QUANTITY);
	};
	
	Timer.prototype.initialize = function()
	{
		this.obDays = this.element.querySelector('[data-entity="timer-days"]');
		this.obHours = this.element.querySelector('[data-entity="timer-hours"]');
		this.obMinutes = this.element.querySelector('[data-entity="timer-minutes"]');
		this.obSeconds = this.element.querySelector('[data-entity="timer-seconds"]');
		this.obQuantity = this.element.querySelector('[data-entity="timer-quantity"]');
		
		this.showTimer = !!this.obDays && !!this.obHours && !!this.obMinutes && !!this.obSeconds;

		
		if (this.timeLeft > 0)
		{
			BX.onCustomEvent('onTimerStart');
		}
		
		if (this.showTimer)
		{
			this.changeInfo();
		}
		this.setQuantity();

		this.timerInterval = window.setInterval($.proxy(function(){

			this.dateNow += 1;
			this.timeLeft = this.options.DATE_TO - this.dateNow;
			
			if (this.timeLeft < 1 && this.options.AUTO_RENEWAL == 'Y')
			{
				while (this.timeLeft < 1)
				{
					this.timeLeft += this.timeLimit;
				}
			}

			if (this.showTimer)
			{
				this.changeInfo();
			}
			
		
			if (this.timeLeft == 0)
			{
				BX.onCustomEvent('onTimerEnd');
				window.clearInterval(this.timerInterval);
			}
			
		}, this), 1000);
	};
	
	Timer.prototype.setQuantity = function () 
	{
		if (!this.obQuantity)
		{
			return;
		}

		if (this.quantity > 0) 
		{
			this.obQuantity.querySelector('[data-entity="timer-quantity-value"]').innerHTML = this.quantity;
		}
		else 
		{
			this.obQuantity.style.display = "none";
		}
	}
	
	Timer.prototype.changeInfo = function()
	{
		if (this.timeLeft >= 0)
		{
			var days = parseInt((this.timeLeft / (60 * 60 )) / 24),
				hourse = parseInt(this.timeLeft / (60 * 60 )),
				hours = parseInt((this.timeLeft / (60 * 60 )) % 24),
				minutes = parseInt(this.timeLeft / (60)) % 60,
				quantity = parseInt(this.quantity),
				seconds = parseInt(this.timeLeft) % 60;
/*
			var widthTimerPerc = false;

			if (!!dataTimer.DINAMICA_DATA)
			{
				if (dataTimer.DINAMICA_DATA == 'evenly')
				{
					widthTimerPerc = Math.floor(100 - ((this.timeLeft / limit) * 100));

					this.$element.find(options.linePercent).css('width', widthTimerPerc + '%');
					this.$element.find(options.textLinePercent).text(widthTimerPerc);
				}
				else
				{
					var prevPerc = false,
							firstPerc = false;

					for (var timePerc in dataTimer.DINAMICA_DATA)
					{
						if (!prevPerc)
						{
							prevPerc = timePerc;
							firstPerc = timePerc;
						}
						if (prevPerc < hourse && hourse < timePerc)
						{
							widthTimerPerc = dataTimer.DINAMICA_DATA[timePerc];
							break;
						}
						prevPerc = timePerc;
					}

					if (!widthTimerPerc)
					{
						if (hourse > prevPerc)
						{
							widthTimerPerc = dataTimer.DINAMICA_DATA[prevPerc];
						}
						else if (hourse < prevPerc)
						{
							widthTimerPerc = dataTimer.DINAMICA_DATA[firstPerc];
						}
					}

					if (widthTimerPerc)
					{
						this.$element.find(options.linePercent).css('width', widthTimerPerc + '%');
						this.$element.find(options.textLinePercent).text(widthTimerPerc);
					}
				}
			}
			else
			{
				widthTimerPerc = Math.floor((this.timeLeft / limit) * 100);
				this.$element.find(options.linePercent).css('width', widthTimerPerc + '%');
				this.$element.find(options.textLinePercent).text(widthTimerPerc);
			}
*/
			if (days < 1)
			{
				this.obDays.style.display = 'none';
				// this.obDays.querySelector('[data-entity="timer-value"]').innerHTML = '00';
				this.obSeconds.style.display = '';
				this.obSeconds.querySelector('[data-entity="timer-value"]').innerHTML = seconds < 10
					? '0' + seconds
					: seconds;
			}
			else if (days > 0)
			{
				this.obDays.style.display = '';
				this.obDays.querySelector('[data-entity="timer-value"]').innerHTML = days < 10
					? '0' + days
					: days;
				this.obSeconds.style.display = 'none';
				// this.obSeconds.querySelector('[data-entity="timer-value"]').innerHTML = '00';
			}

			this.obMinutes.querySelector('[data-entity="timer-value"]').innerHTML = minutes < 10
				? '0' + minutes
				: minutes;
			this.obHours.querySelector('[data-entity="timer-value"]').innerHTML = hours < 10
				? '0' + hours
				: hours;
		}
	};

	return Timer;
})(jQuery);

export default Timer;
