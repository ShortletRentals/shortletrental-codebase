import React, { useEffect, useState } from 'react'
import { Alert, Image, StatusBar, StyleSheet, Text, View } from 'react-native'
import Images from '../../styles/Images'
import { useNetInfo } from '@react-native-community/netinfo';
import { useNavigation } from '@react-navigation/native';

const SplashScreen = () => {



  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#fff' }}>
      <StatusBar hidden />
      <Image source={Images.SplashIcon} />
    </View>
  )
}

export default SplashScreen

const styles = StyleSheet.create({
  centered: {
    alignItems: "center",
    flex: 1,
    justifyContent: "center",
  },
  title: {
    fontSize: 20,
    fontWeight: "bold",
    textAlign: "center",
  },
})
