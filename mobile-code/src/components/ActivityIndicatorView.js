import { StyleSheet, Text, View,ActivityIndicator } from 'react-native'
import React from 'react'

const ActivityIndicatorView = () => {
  return (
    <View style={styles.indicator}>
      <ActivityIndicator size={'large'} color='#000'/>
    </View>
  )
}

export default ActivityIndicatorView

const styles = StyleSheet.create({
  indicator:{
    flex:1,
    justifyContent:'center',
    alignItems:'center'
  }
})