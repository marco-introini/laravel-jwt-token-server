package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"strings"

	"github.com/golang-jwt/jwt/v5"
)

// Verifica e decodifica il token
func verifyToken(tokenString string) (*jwt.Token, error) {
	publicKeyData, _ := os.ReadFile("../storage/app/keys/rsa_public_key.pem")
	publicKey, err := jwt.ParseRSAPublicKeyFromPEM(publicKeyData)
	if err != nil {
		return nil, err
	}

	token, err := jwt.Parse(tokenString, func(token *jwt.Token) (interface{}, error) {
		if _, ok := token.Method.(*jwt.SigningMethodRSA); !ok {
			return nil, fmt.Errorf("unexpected signing method: %v", token.Header["alg"])
		}
		return publicKey, nil
	})

	if err != nil {
		return nil, err
	}

	if !token.Valid {
		return nil, fmt.Errorf("token is not valid")
	}

	return token, nil
}

// Handler per la verifica del token JWT
func tokenHandler(w http.ResponseWriter, r *http.Request) {
	authHeader := r.Header.Get("Authorization")
	if authHeader == "" {
		http.Error(w, "Authorization header missing", http.StatusUnauthorized)
		return
	}

	tokenStr := strings.TrimPrefix(authHeader, "Bearer ")

	token, err := verifyToken(tokenStr)
	if err != nil {
		http.Error(w, fmt.Sprintf("Invalid token: %v", err), http.StatusUnauthorized)
		return
	}

	claims, ok := token.Claims.(jwt.MapClaims)
	if !ok {
		http.Error(w, "Cannot parse claims", http.StatusInternalServerError)
		return
	}

	response := fmt.Sprintf("Token is valid. Claims: %v", claims)
	w.Write([]byte(response))
}

func main() {
	http.HandleFunc("/verify", tokenHandler)
	fmt.Println("Starting server on :9090...")
	log.Fatal(http.ListenAndServe(":9090", nil))
}
