<?php

namespace Tiatere\Application;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactRequest
{
    private $validator;

    public function __construct(
      ValidatorInterface $validator,
      EventDispatcherInterface $eventDispatcher
    ) {
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ContactCommand $command
     * @return bool
     * @internal param ContactCommand $request
     */
    public function execute(ContactCommand $command)
    {
        $fullname = $command->fullname();
        $email = $command->email();
        $query = $command->query();

        $this->assertEmailsIsValid($email);

        $this->eventDispatcher->dispatch('contact.requested', new ContactRequested($fullname, $email, $query));

        return true;
    }

    private function assertEmailsIsValid($email) {
        $errors = $this->validator->validate($email, new Assert\Email());

        if (count($errors) > 0) {
            throw new WrongEmailFormatException();
        }
    }
}