import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import Header from '../../components/Header'
import { BackHandler } from 'react-native'
import { useEffect } from 'react'

const AddPropertySchedule = (props) => {
    const data = [
        {
            title:'Lagos -In person property inspection',
            content:'Kindly select the time that works best...'
        },
        {
            title:'Abuja- In person property Inspection',
            content:'Kindly select the time that works best...'
        },
        {
            title:'Port Harcourt-In person property Inspection',
            content:'Kindly select the time that works best...'
        },
        {
            title:'Other states-In person property Inspection',
            content:'Kindly select the time that works best...'
        },
    ]
    const handler = () => {
        props.navigation.goBack()
      }
      useEffect(() => {
        const backAction = () => {
          handler()
          return true;
        };
    
        const backHandler = BackHandler.addEventListener(
          'hardwareBackPress',
          backAction,
        );
    
        return () => backHandler.remove();
      }, [props])
  return (
    <View>
        <Header Heading={'Schedule Appointment'} onPress={()=>props.navigation.navigate('BecomeHost')}/>
        <View style={{marginHorizontal:20,marginTop:10}}>
        {
            data.map((item,index)=>(
                <TouchableOpacity onPress={()=>props.navigation.navigate('AddPropertyScheduleDate')} style={{height:90,width:'100%',backgroundColor:'#F7F6FC',justifyContent:'center',alignItems:'flex-start',marginTop:16,borderWidth:1,borderColor:'#F99428',borderRadius:12}}>
                    <Text style={{color:'#F1592A',fontSize:18,fontWeight:'800',marginLeft:12}}>{item.title}</Text>
                    <Text numberOfLines={1} style={{color:'#000000',fontSize:15,fontWeight:'600',marginLeft:12}}>{item.content}</Text>
                </TouchableOpacity>
            ))
        }
        </View>
    </View>
  )
}

export default AddPropertySchedule

const styles = StyleSheet.create({})