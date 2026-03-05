{{-- resources/views/emails/admin-chatbot-reply.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>HealthNet Support Reply</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:30px 0">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0"
             style="background:#fff;border-radius:16px;overflow:hidden;
                    box-shadow:0 4px 24px rgba(13,110,253,.10);max-width:600px">

        {{-- ── Header ── --}}
        <tr>
          <td style="background:linear-gradient(135deg,#198754 0%,#146c43 100%);
                     padding:32px 30px;text-align:center">
            <div style="background:rgba(255,255,255,0.15);width:60px;height:60px;
                        border-radius:50%;display:inline-flex;align-items:center;
                        justify-content:center;margin-bottom:14px;font-size:26px">
              🏥
            </div>
            <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700;
                       letter-spacing:.3px">HealthNet Live Support</h1>
            <p style="color:rgba(255,255,255,.85);margin:6px 0 0;font-size:13px">
              A support agent has replied to your message
            </p>
          </td>
        </tr>

        {{-- ── Body ── --}}
        <tr>
          <td style="padding:30px 32px">

            {{-- Greeting --}}
            <p style="font-size:16px;font-weight:600;color:#1e293b;margin:0 0 6px">
              Hello {{ $toName }},
            </p>
            <p style="font-size:14px;color:#475569;margin:0 0 24px;line-height:1.6">
              Our support team has reviewed your message and sent you a reply below.
            </p>

            {{-- Admin Reply Box --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px">
              <tr>
                <td style="background:#e8f5e9;border-left:4px solid #198754;
                           border-radius:10px;padding:16px 18px">
                  <p style="margin:0 0 6px;font-size:11px;font-weight:700;
                             color:#198754;text-transform:uppercase;letter-spacing:.6px">
                    💬 Support Agent Reply
                  </p>
                  <p style="margin:0;color:#1e293b;font-size:15px;
                             line-height:1.7;white-space:pre-wrap">{{ $replyMessage }}</p>
                </td>
              </tr>
            </table>

            {{-- Replied by --}}
            <p style="font-size:12px;color:#94a3b8;margin:0 0 24px;text-align:right">
              Replied by: <strong style="color:#64748b">{{ $adminName }}</strong> ·
              Conversation #{{ $convId }}
            </p>

            {{-- Divider --}}
            <hr style="border:none;border-top:1px solid #e2e8f0;margin:0 0 22px">

            {{-- Continue chat note --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px">
              <tr>
                <td style="background:#f0f4ff;border:1px solid #c7d7fd;
                           border-radius:10px;padding:14px 16px">
                  <p style="margin:0;font-size:13px;color:#1e3a8a;line-height:1.6">
                    💬 <strong>Need further help?</strong><br>
                    Visit <a href="{{ $appUrl }}"
                             style="color:#0d6efd;text-decoration:none;font-weight:600">
                      {{ $appUrl }}
                    </a>
                    and use the <strong>Live Chat</strong> widget to continue the conversation.
                  </p>
                </td>
              </tr>
            </table>

            {{-- Warning --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px">
              <tr>
                <td style="background:#fff3cd;border:1px solid #ffc107;
                           border-radius:8px;padding:12px 14px">
                  <p style="margin:0;font-size:12px;color:#856404;line-height:1.5">
                    ⚠️ <strong>Important:</strong>
                    This reply is provided as general guidance only.
                    Always consult a qualified doctor for diagnosis and treatment.
                  </p>
                </td>
              </tr>
            </table>

            {{-- CTA Button --}}
            <div style="text-align:center">
              <a href="{{ $appUrl }}"
                 style="display:inline-block;
                        background:linear-gradient(135deg,#198754,#146c43);
                        color:#fff;padding:13px 32px;border-radius:25px;
                        text-decoration:none;font-weight:600;font-size:14px;
                        letter-spacing:.3px">
                Visit HealthNet →
              </a>
            </div>

          </td>
        </tr>

        {{-- ── Footer ── --}}
        <tr>
          <td style="background:#f8faff;padding:18px 32px;text-align:center;
                     border-top:1px solid #e2e8f0">
            <p style="margin:0 0 4px;font-size:12px;color:#64748b">
              <a href="{{ $appUrl }}" style="color:#0d6efd;text-decoration:none;font-weight:600">
                HealthNet
              </a>
              &bull; Sri Lanka's Healthcare Platform
            </p>
            <p style="margin:0;font-size:11px;color:#94a3b8">
              This email was sent because you contacted HealthNet via the chat widget.
              Conversation #{{ $convId }}
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
