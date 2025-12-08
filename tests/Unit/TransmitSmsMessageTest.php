<?php

declare(strict_types=1);

use ExpertSystems\TransmitSms\Laravel\Notifications\TransmitSmsMessage;

describe('TransmitSmsMessage', function () {
    describe('construction', function () {
        it('creates message with content', function () {
            $message = new TransmitSmsMessage('Hello World');
            expect($message->getContent())->toBe('Hello World');
        });

        it('creates message with empty content by default', function () {
            $message = new TransmitSmsMessage;
            expect($message->getContent())->toBe('');
        });
    });

    describe('fluent builder', function () {
        it('sets content via fluent method', function () {
            $message = (new TransmitSmsMessage)
                ->content('Hello World');

            expect($message->getContent())->toBe('Hello World');
        });

        it('sets recipient via to() method', function () {
            $message = (new TransmitSmsMessage('Test'))
                ->to('61400000000');

            expect($message->getTo())->toBe('61400000000');
        });

        it('sets sender via from() method', function () {
            $message = (new TransmitSmsMessage('Test'))
                ->from('MyBrand');

            expect($message->getFrom())->toBe('MyBrand');
        });

        it('sets scheduled time via sendAt() method', function () {
            $message = (new TransmitSmsMessage('Test'))
                ->sendAt('2025-12-06 10:00:00');

            expect($message->getSendAt())->toBe('2025-12-06 10:00:00');
        });

        it('chains all methods together', function () {
            $message = (new TransmitSmsMessage)
                ->content('Hello World')
                ->to('61400000000')
                ->from('MyBrand')
                ->sendAt('2025-12-06 10:00:00');

            expect($message->getContent())->toBe('Hello World');
            expect($message->getTo())->toBe('61400000000');
            expect($message->getFrom())->toBe('MyBrand');
            expect($message->getSendAt())->toBe('2025-12-06 10:00:00');
        });

        it('returns self for fluent chaining', function () {
            $message = new TransmitSmsMessage;

            expect($message->content('Test'))->toBe($message);
            expect($message->to('61400000000'))->toBe($message);
            expect($message->from('MyBrand'))->toBe($message);
            expect($message->sendAt('2025-12-06 10:00:00'))->toBe($message);
            expect($message->options(['key' => 'value']))->toBe($message);
        });
    });

    describe('options', function () {
        it('sets options via options() method', function () {
            $message = (new TransmitSmsMessage('Test'))
                ->options(['validity' => 60]);

            expect($message->getOptions())->toBe(['validity' => 60]);
        });

        it('merges options on multiple calls', function () {
            $message = (new TransmitSmsMessage('Test'))
                ->options(['validity' => 60])
                ->options(['country_code' => 'AU']);

            expect($message->getOptions())->toBe([
                'validity' => 60,
                'country_code' => 'AU',
            ]);
        });

        it('overwrites existing options with same key', function () {
            $message = (new TransmitSmsMessage('Test'))
                ->options(['validity' => 60])
                ->options(['validity' => 120]);

            expect($message->getOptions())->toBe(['validity' => 120]);
        });

        it('returns empty array by default', function () {
            $message = new TransmitSmsMessage('Test');
            expect($message->getOptions())->toBe([]);
        });
    });

    describe('default values', function () {
        it('returns null for unset to', function () {
            $message = new TransmitSmsMessage('Test');
            expect($message->getTo())->toBeNull();
        });

        it('returns null for unset from', function () {
            $message = new TransmitSmsMessage('Test');
            expect($message->getFrom())->toBeNull();
        });

        it('returns null for unset sendAt', function () {
            $message = new TransmitSmsMessage('Test');
            expect($message->getSendAt())->toBeNull();
        });
    });
});
