<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNote extends Model
{
    use HasFactory;
    protected $fillable = ['content'];

    // Helper to set a professional default script if not present
    public static function setDefaultScriptIfEmpty()
    {
        $note = self::firstOrCreate(['id' => 1]);
        if (empty($note->content)) {
            $note->content = '<h3>Professional Call Script</h3>\n<p><strong>Greeting:</strong><br>Hello, this is <em>[Your Name]</em> calling from <em>[Company Name]</em>. May I speak with <em>[Customer Name]</em>?</p>\n<p><strong>Purpose:</strong><br>I am reaching out regarding <em>[reason for call]</em>. I would like to share some information that could be valuable for you.</p>\n<ul>\n  <li>Briefly introduce the product/service/offer.</li>\n  <li>Highlight one or two key benefits.</li>\n</ul>\n<p><strong>Engagement:</strong><br>Do you have a moment to discuss this now?</p>\n<p><strong>Objection Handling:</strong><br>If you have any questions or concerns, I am happy to address them.</p>\n<p><strong>Next Steps:</strong><br>Would you be interested in learning more or scheduling a follow-up call?</p>\n<p><strong>Closing:</strong><br>Thank you for your time, <em>[Customer Name]</em>. Have a great day!</p>';
            $note->save();
        }
    }
}
