import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, ActivityIndicator, Button, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
import { useRouter } from 'expo-router'; // Importez le hook useRouter
import { SERVER_IP } from '@env';

const Profile = () => {
  const [userData, setUserData] = useState(null);
  const [loading, setLoading] = useState(true);
  const router = useRouter(); // Initialisez le hook useRouter

  useEffect(() => {
    fetchUserData();
  }, []);

  const fetchUserData = async () => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      const response = await axios.get(`http://${SERVER_IP}:8000/api/auth/me`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      const data = response.data;
      setUserData(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching user data:', error);
      setLoading(false);
    }
  };

  const handleLogout = async () => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      await axios.post(`http://${ SERVER_IP }:8000/api/auth/logout`, null, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      await AsyncStorage.removeItem('loginToken');
      router.push('/login'); // Rediriger vers la page de connexion après la déconnexion
    } catch (error) {
      console.error('Error logging out:', error);
      Alert.alert('Error', 'Failed to logout');
    }
  };

  const renderItem = ({ item }) => (
    <View style={styles.item}>
      <Text style={styles.label}>{item.label}:</Text>
      <Text>{item.value}</Text>
    </View>
  );

  if (loading) {
    return (
      <View style={[styles.container, styles.loadingContainer]}>
        <ActivityIndicator size="large" color="#0000ff" />
      </View>
    );
  }

  if (!userData) {
    return (
      <View style={styles.container}>
        <Text>No user data available.</Text>
      </View>
    );
  }

  // Filter user data to keep only desired fields
  const filteredData = {
    nom: userData.nom,
    prenom: userData.prenom,
    date_de_naissance: userData.date_de_naissance,
    adresse_mail: userData.adresse_mail,
    telephone: userData.telephone,
    ville: userData.adresse.ville,
    code_postal: userData.adresse.code_postal,
    nom_voie: userData.adresse.nom_voie,
    numero_voie: userData.adresse.numero_voie,
    nom_role: userData.role.nom_role,
  };

  // Convert filtered data to array of { label, value } objects
  const userDataArray = Object.entries(filteredData).map(([key, value]) => ({
    label: key,
    value: value.toString(), // Convert value to string for simplicity
  }));

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Informations utilisateur :</Text>
      <FlatList
        data={userDataArray}
        renderItem={renderItem}
        keyExtractor={(item) => item.label}
      />
      <View style={styles.buttonContainer}>
        <Button title="Déconnexion" color="red" onPress={handleLogout} />
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    paddingTop: 15,
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 20,
    backgroundColor: '#f9f9f9',
  },
  loadingContainer: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 10,
    color: '#333',
  },
  item: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderBottomWidth: 1,
    borderBottomColor: '#ccc',
    paddingVertical: 10,
    width: '100%',
  },
  label: {
    fontWeight: 'bold',
    marginRight: 10,
  },
  buttonContainer: {
    marginBottom: 175,
    width: '50%', // Adjust width as needed
  },
});

export default Profile;
