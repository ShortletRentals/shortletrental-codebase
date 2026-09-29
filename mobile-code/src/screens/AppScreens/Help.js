import {StyleSheet, Text, View} from 'react-native';
import React from 'react';
import Header from '../../components/Header';
import {useNavigation} from '@react-navigation/native';

const Help = () => {
  const navigation = useNavigation();
  return (
    <View>
      <Header
        Heading={'Help Center'}
        onPress={() => navigation.navigate('Setting')}
      />
      <Text>Help</Text>
    </View>
  );
};

export default Help;

const styles = StyleSheet.create({});
