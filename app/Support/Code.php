<?php
namespace App\Support;
class Code {
  public static function order(): string {
    return 'ORD-'.now()->format('Ymd').'-'.strtoupper(str()->random(6));
  }
  public static function ticketSerial(): string {
    return 'TIX-'.strtoupper(str()->random(10));
  }
  public static function qr(): string {
    return hash('sha256', uniqid('', true).str()->random(16));
  }
}
