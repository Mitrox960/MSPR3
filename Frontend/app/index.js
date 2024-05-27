import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Link } from 'expo-router';

export default function Page() {
  return (
    <View style={styles.container} >
      <Text>Bienvenue sur Arosaje! </Text>
      <Link href="/register" style={styles.link}>Créer mon compte</Link>
      <Link href="/login" style={styles.link}>J'ai déjà un compte</Link>
    </View>
  );
}

const styles = StyleSheet.create({
  container : {
    flex: 1,
    backgroundColor: '#CFFFD4',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,

  },
  link: {
    backgroundColor: '#2BDB3E',
    borderColor: '#009210',
    borderWidth: 1,
    borderRadius: 5,
    padding: 10,
    marginBottom: 10,
    marginVertical: 30,
    paddingHorizontal: 50,
  },
  
});
