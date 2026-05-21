(function () {
	"use strict";

	$('.js-chzn-select').chosen({ height: '100%', width: '100%', search_contains: true });

	function updateStep() {
		const currentStep = $(".booking-step.active");

		/* BACK BUTTON */
		if (currentStep.is($(".booking-step").first())) {
			$(".btn-back-step").removeClass("active");
		} else {
			$(".btn-back-step").addClass("active");
		}
	}

	/* NEXT */
	$(".btn-next-step").on("click", () => {
		const currentStep = $(".booking-step.active");
		const currentProgressStep = $(".progress-step.progress-active").last();
		const nextProgressStep = currentProgressStep.next(".progress-step");
		const nextStep = currentStep.next(".booking-step");

		if (nextStep.length) {
			currentStep.removeClass("active");
			nextStep.addClass("active");
			currentProgressStep.addClass("progress-activated").removeClass("progress-active");
			nextProgressStep.addClass("progress-active");
			updateStep();
		}
	});

	/* BACK */
	$(".btn-back-step").on("click", () => {
		const currentStep = $(".booking-step.active");
		const prevStep = currentStep.prev(".booking-step");
		const currentProgressStep = $(".progress-step.progress-active").last();
		const prevProgressStep = $(".progress-step.progress-active").prev(".progress-step");

		if (prevStep.length) {
			currentStep.removeClass("active");
			prevStep.addClass("active");
			currentProgressStep.removeClass("progress-active");
			prevProgressStep.addClass("progress-active").removeClass("progress-activated");
			updateStep();
		}
	});

	updateStep();

})();