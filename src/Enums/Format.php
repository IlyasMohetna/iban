<?php

namespace IlyasMohetna\Iban\Enums;

enum Format: string
{
    case ELECTRONIC = 'electronic';
    case PRINT = 'print';
    case ANONYMIZED = 'anonymized';
}
