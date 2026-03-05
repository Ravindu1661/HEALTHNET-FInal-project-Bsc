{{-- resources/views/emails/chatbot-reply.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:'Segoe UI',Arial,sans-serif;background:#f0f4ff;margin:0;padding:20px">
  <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(13,110,253,0.1)">
    <div style="background:linear-gradient(135deg,#0d6efd,#0a58ca);padding:28px 30px;text-align:center">
      <div style="background:rgba(255,255,255,0.15);width:56px;height:56px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:24px">🏥</div>
      <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700">HealthNet AI Assistant</h1>
      <p style="color:rgba(255,255,255,.85);margin:4px 0 0;font-size:13px">Your health consultation reply</p>
    </div>
    <div style="padding:28px 30px">
      <p style="color:#1e293b;font-size:15px;margin:0 0 20px">Dear <strong>{{ $name }}</strong>,</p>
      <p style="color:#475569;font-size:14px;margin:0 0 20px">Thank you for contacting HealthNet. Here is the AI response to your query:</p>

      <div style="background:#f0f4ff;border-left:4px solid #0d6efd;border-radius:8px;padding:14px 16px;margin-bottom:16px">
        <p style="margin:0 0 4px;font-size:12px;font-weight:600;color:#0d6efd;text-transform:uppercase">Your Question</p>
        <p style="margin:0;color:#1e293b;font-size:14px">{{ $userMessage }}</p>
      </div>

      <div style="background:#e8f5e9;border-left:4px solid #198754;border-radius:8px;padding:14px 16px;margin-bottom:24px">
        <p style="margin:0 0 4px;font-size:12px;font-weight:600;color:#198754;text-transform:uppercase">HealthNet AI Response</p>
        <p style="margin:0;color:#1e293b;font-size:14px;line-height:1.6">{{ $botReply }}</p>
      </div>

      <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:12px 14px;margin-bottom:20px">
        <p style="margin:0;font-size:12px;color:#856404">⚠️ <strong>Important:</strong> This is AI-generated guidance only. Always consult a qualified doctor for diagnosis and treatment.</p>
      </div>

      <div style="text-align:center;margin-top:24px">
        <a href="{{ url('/') }}" style="display:inline-block;background:linear-gradient(135deg,#0d6efd,#0a58ca);color:#fff;padding:12px 28px;border-radius:25px;text-decoration:none;font-weight:600;font-size:14px">Visit HealthNet →</a>
      </div>
    </div>
    <div style="background:#f8faff;padding:16px 30px;text-align:center;border-top:1px solid #e2e8f0">
      <p style="margin:0;font-size:12px;color:#94a3b8">© {{ date('Y') }} HealthNet | Sri Lanka's Healthcare Platform</p>
    </div>
  </div>
</body>
</html>
