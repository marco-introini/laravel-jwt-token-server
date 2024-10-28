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

## ES256

```bash
# Generate a private key (prime256v1 is the name of the parameters used
# to generate the key, this is the same as P-256 in the JWA spec). 
openssl ecparam -name prime256v1 -genkey -noout -out ./storage/app/keys/ecdsa_private_key.pem
# Derive the public key from the private key
openssl ec -in ./storage/app/keys/ecdsa_private_key.pem -pubout -out ./storage/app/keys/ecdsa_public_key.pem
```

