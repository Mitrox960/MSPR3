import React from 'react';
import { View, StyleSheet, TouchableOpacity, Text } from 'react-native';
import { Link } from 'expo-router';
import { Image } from 'react-native';

export default function Home() {
  return (
    <View style={styles.container}>
      <View style={styles.view}>
      <Text style={styles.title}>Bienvenue sur Arosaje</Text>
        <Image source={require('../assets/images/Arosaje.png')} style={styles.imageindex} />
      </View>
      
      <View style={styles.containernav}>
        <TouchableOpacity style={styles.buttonindex}>
          <Link href="/addPlant" style={styles.link}>
            <Image source={require('../assets/images/logohomeaddplant.png')} style={styles.image} />
          </Link>
        </TouchableOpacity>
        <TouchableOpacity style={styles.buttonindex}>
          <Link href="/myPlants" style={styles.link}>
            <Image source={require('../assets/images/logohomeplant.png')} style={styles.image} />
          </Link>
        </TouchableOpacity>
        <TouchableOpacity style={styles.buttonindex}>
          <Link href="/findPlants" style={styles.link}>
            <Image source={require('../assets/images/logohomesearch.png')} style={styles.image} />
          </Link>
        </TouchableOpacity>
        <TouchableOpacity style={styles.buttonindex}>
          <Link href="/map" style={styles.link}>
            <Image source={require('../assets/images/logohomemap.png')} style={styles.image} />
          </Link>
        </TouchableOpacity>
        <TouchableOpacity style={styles.buttonindex}>
          <Link href="/profile" style={styles.link}>
            <Image source={require('../assets/images/logohomeprofile.png')} style={styles.image} />
          </Link>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  view: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  containernav: {
    justifyContent: 'row',
    alignSelf: 'flex-end',
    flexDirection: 'row',
  },
  buttonindex: {
    borderRightColor: 'grey',
    borderRightWidth: 1,
    flex: 1,
    alignItems: 'center',
  },
  image: {
    width: 50,
    height: 50,
  },
  title: {
    textAlign: 'center',
    fontWeight: 'bold',
    marginBottom: 15,
    fontSize: 20,
  },
  link: {
    height: 100,
    justifyContent: 'center',
    alignItems: 'center',
  },
  imageindex: {
    width: 300, // ajustez la largeur de l'image selon vos préférences
    height: 250, // ajustez la hauteur de l'image selon vos préférences,
    borderRadius: 25,
    
  }
});
