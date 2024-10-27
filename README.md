Example project using JWT tokens in Laravel without any external dedicated package

## HS256


## RS256

### Certificate creation

```bash
# Generate a private key
openssl genpkey -algorithm RSA -out ./storage/app/keys/rsa_private_key.pem -pkeyopt rsa_keygen_bits:2048
# Derive the public key from the private key
openssl rsa -pubout -in ./storage/app/keys/rsa_private_key.pem -out ./storage/app/keys/rsa_public_key.pem
```

