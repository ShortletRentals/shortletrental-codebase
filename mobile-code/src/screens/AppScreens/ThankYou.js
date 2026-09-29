import { BackHandler, StyleSheet, Text, View } from 'react-native'
import React, { useEffect } from 'react'
import { TouchableOpacity } from 'react-native'
import { Image } from 'react-native'
import Images from '../../styles/Images'
import { color } from '../../styles/colors'
import { useNavigation } from '@react-navigation/native'

const ThankYou = (props) => {
    
    useEffect(() => {
        const backHandler = BackHandler.addEventListener('hardwareBackPress', () => true)
        return () => backHandler.remove()
      }, [])
  
  return (
        <View style={{ flex:1,justifyContent:'center',alignItems:'center' }}>
          {/* <TouchableOpacity
            style={{ padding: 16 }}
            onPress={() => actionSheetRef?.current?.hide()}>
            <Image source={Images.crossIcon} style={{ height: 24, width: 24 }} />
          </TouchableOpacity> */}
          <TouchableOpacity style={{ padding: 16, marginTop: 10 }}>
            <Image
              source={Images.rightSignIcon}
              style={{ height: 71, width: 71, alignSelf: 'center' }}
            />
          </TouchableOpacity>
          {/* <Text>THANK YOU!</Text> */}
          <Text
            style={{
              marginHorizontal: 20,
              marginTop: 20,
              fontSize: 18,
              color: '#000000',
              paddingHorizontal: 20,
              textAlign: 'center'
            }}>
            Thank for for sharing details with us. Shortlet... Team will share
            details email to you very soon.
          </Text>
          <TouchableOpacity
            activeOpacity={0.5}
            onPress={
              () => props?.navigation?.navigate('BeforHome')
              // () => props.navigation.navigate('BottomTab')
            }
            style={{
              marginTop: 40,
              backgroundColor: color.appOrangeColor,
              width: '70%',
              // padding: 10,
              height: 50,
              borderRadius: 10,
              justifyContent: 'center',
              marginHorizontal: 20,
              alignSelf: 'center',
            }}>
            <View
              style={{
                position: 'absolute',
                flex: 1,
              }}>
              <Image
                source={Images.whiteDot}
                style={{
                  paddingLeft: 70,
                  width: 29,
                  height: 29,
                  resizeMode: 'contain',
                }}
              />
            </View>

            <Text
              style={{
                textAlign: 'center',
                fontSize: 16,
                color: color.appWhiteColor,
              }}>
              Back to Home
            </Text>
          </TouchableOpacity>
        </View>
  )
}

export default ThankYou

const styles = StyleSheet.create({})