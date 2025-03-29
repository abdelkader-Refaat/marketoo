<?php

namespace App\Enums;

enum ComplaintTypesEnum: int
{
    case Complaint = 1;
    case Enquiry = 2;
    case Question = 3;
    case Other = 4;
}
