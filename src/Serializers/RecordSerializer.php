<?php

namespace App\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class RecordSerializer extends AbstractSerializer
{
    protected $type = 'record';

    public function getAttributes($record, array $fields = null)
    {
        return [
            'id' => $record->getId(),
            'book_id' => $record->getBookId(),
            'user_id' => $record->getUserId(),
            'issue_date'  => $record->getIssueDate(),
            'return_date' => $record->getReturnDate()
        ];
    }
}