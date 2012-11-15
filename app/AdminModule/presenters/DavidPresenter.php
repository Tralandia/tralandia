<?php

namespace AdminModule;


class DavidPresenter extends BasePresenter {


	public function actionList() {

		$robot = $this->context->generatePathSegmentsRobot;
		$robot->run();


		// // pripravim si template a layout
		// $template = $this->context->emailTemplateRepositoryAccessor->get()->find(1);
		// $layout = $this->context->emailLayoutRepositoryAccessor->get()->find(1);

		// // pripravim si odosielatela
		// $sender = $this->context->userRepositoryAccessor->get()->findOneByLogin('infoubytovanie@gmail.com');

		// // pripravim si prijimatela
		// $receiver = $this->context->userRepositoryAccessor->get()->findOneByLogin('pavol@paradeiser.sk');

		// // pripravim si rental
		// $rental = $this->context->rentalRepositoryAccessor->get()->find(1);

		// // ponastavujem compiler
		// $emailCompiler = $this->context->emailCompiler;
		// $emailCompiler->setTemplate($template);
		// $emailCompiler->setLayout($layout);
		// $emailCompiler->setPrimaryVariable('receiver', 'visitor', $receiver);
		// $emailCompiler->addVariable('sender', 'visitor', $sender);
		// $emailCompiler->addVariable('rental', 'rental', $rental);
		// $emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		// $html = $emailCompiler->compile();

		// $this->sendResponse(new TextResponse($html));
	}
	

}
