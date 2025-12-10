# API Authentication: API Key vs HMAC Signing

## Overview

This document explains the difference between API Key authentication and HMAC signing for API endpoints, specifically in the context of this sensor monitoring application.

## API Key Authentication

### How It Works

**Client Side:**
```python
# Simple: Just send the API key in header
headers = {
    "X-API-Key": "your-api-key-here",
    "Content-Type": "application/json"
}
data = {
    "sensor_name": 1,
    "value": 25.5,
    "unit": "Celsius",
    "type": "temperature"
}
response = requests.post(url, json=data, headers=headers)
```

**Server Side:**
```php
// Middleware checks if API key matches
$apiKey = $request->header('X-API-Key');
$validApiKey = config('app.api_key');

if ($apiKey !== $validApiKey) {
    return response()->json(['error' => 'Unauthorized'], 401);
}
```

### Characteristics

- ✅ **Simple**: Easy to implement on both client and server
- ✅ **Straightforward**: Just compare keys
- ✅ **Works well with HTTPS**: Encryption prevents key interception
- ⚠️ **No tamper detection**: If key is compromised, attacker can modify requests
- ⚠️ **No replay protection**: Same request can be sent multiple times

### Flow

```
Client → Sends: API key in header
Server → Compares: received_key == stored_key
Result: Authenticates sender only
```

---

## HMAC Signing

### How It Works

**Client Side:**
```python
import hmac
import hashlib
import time

# 1. Create the message to sign (request data + timestamp)
timestamp = str(int(time.time()))
method = "POST"
path = "/api/sensor-data"
body = json.dumps(data)
message = f"{timestamp}{method}{path}{body}"

# 2. Create signature using secret (NOT the API key itself)
secret_key = "your-shared-secret-key"
signature = hmac.new(
    secret_key.encode(),
    message.encode(),
    hashlib.sha256
).hexdigest()

# 3. Send request WITH the original message AND signature
headers = {
    "X-Timestamp": timestamp,
    "X-Signature": signature,  # The signature, not the secret!
    "Content-Type": "application/json"
}
response = requests.post(url, json=data, headers=headers)
```

**Server Side:**
```php
// 1. Reconstruct the same message
$timestamp = $request->header('X-Timestamp');
$method = $request->method();
$path = $request->path();
$body = $request->getContent();
$message = $timestamp . $method . $path . $body;

// 2. Recreate the signature using the SAME secret
$secretKey = config('app.hmac_secret');
$expectedSignature = hash_hmac('sha256', $message, $secretKey);

// 3. Compare signatures (NOT "unhashing")
$receivedSignature = $request->header('X-Signature');

if ($receivedSignature !== $expectedSignature) {
    return response()->json(['error' => 'Invalid signature'], 401);
}

// 4. Optional: Check timestamp to prevent replay attacks
$requestTime = (int) $timestamp;
$currentTime = time();
if (abs($currentTime - $requestTime) > 300) { // 5 minutes
    return response()->json(['error' => 'Request expired'], 401);
}
```

### Characteristics

- ✅ **Tamper detection**: If request is modified, signature won't match
- ✅ **Replay protection**: Timestamp prevents old requests from being reused
- ✅ **Secret never sent**: Only signature travels over network
- ⚠️ **More complex**: Requires more code on both client and server
- ⚠️ **Clock sync required**: Client and server clocks must be reasonably synchronized

### Flow

```
Client → Sends: message + signature (created with secret)
Server → Recreates signature, compares
Result: Authenticates sender AND proves message wasn't tampered with
```

---

## Key Differences

| Feature | API Key | HMAC Signing |
|---------|---------|-------------|
| **Complexity** | Simple | More complex |
| **Authentication** | ✅ Yes | ✅ Yes |
| **Tamper Detection** | ❌ No | ✅ Yes |
| **Replay Protection** | ❌ No | ✅ Yes (with timestamp) |
| **Secret Transmission** | Key sent in request | Secret never sent |
| **HTTPS Dependency** | Recommended | Recommended |
| **Use Case** | Simple APIs, single device | High-security APIs, multiple clients |

---

## Important Notes

### HMAC Doesn't "Unhash"

**Common Misconception:** HMAC "unhashes" the signature to reveal the message.

**Reality:** 
- The server **recreates** the signature using the same secret
- If signatures match, the request is authentic and untampered
- The secret never travels over the network
- The message itself is sent in the request (not hidden)

### Why HMAC is More Secure

1. **Request Integrity**: If someone changes the payload, signature won't match
2. **Replay Protection**: Timestamp prevents old requests from being reused
3. **Secret Protection**: Only signature is sent, not the secret itself

### Why API Key is Fine for This Project

- ✅ Using HTTPS (encryption prevents tampering)
- ✅ Single device (Raspberry Pi)
- ✅ Simple to implement
- ✅ Sufficient security for personal project

---

## When to Use Each

### Use API Key When:
- Simple authentication is sufficient
- Using HTTPS (encryption provides tamper protection)
- Single or few devices/clients
- Personal or small-scale projects
- **This project's use case** ✅

### Use HMAC When:
- High-security requirements
- Multiple clients/devices
- Need tamper detection beyond HTTPS
- Need replay attack protection
- Public APIs with many clients
- Regulatory/compliance requirements

---

## Security Best Practices

### For API Key:
1. ✅ Always use HTTPS
2. ✅ Store key securely (environment variables)
3. ✅ Rotate keys if compromised
4. ✅ Use long, random keys (32+ characters)
5. ✅ Block HTTP entirely on server

### For HMAC:
1. ✅ Always use HTTPS
2. ✅ Include timestamp for replay protection
3. ✅ Use short time windows (5-15 minutes)
4. ✅ Store secret securely (environment variables)
5. ✅ Rotate secrets periodically

---

## Implementation in This Project

**Current Implementation:** API Key Authentication

**Location:**
- Middleware: `app/Http/Middleware/VerifyApiKey.php`
- Config: `config/app.php` (reads from `API_KEY` env variable)
- Routes: `routes/api.php` (protected with `api.key` middleware)

**Why API Key:**
- Simple and sufficient for single Raspberry Pi device
- HTTPS provides encryption
- Easy to implement and maintain
- No need for complex HMAC signing

---

## References

- [Laravel Middleware Documentation](https://laravel.com/docs/middleware)
- [HMAC Wikipedia](https://en.wikipedia.org/wiki/HMAC)
- [OWASP API Security](https://owasp.org/www-project-api-security/)

