import { StyleSheet, Text, View, TouchableOpacity, Image, ScrollView } from 'react-native'
import React, { useState, useMemo } from 'react'
import Header from '../../components/Header'
import { Calendar } from 'react-native-calendars'
import DateTimePicker from '@react-native-community/datetimepicker'
import { color } from '../../styles/colors'
import { width } from '../../styles/style'
import Images from '../../styles/Images'
import { useCallback } from 'react'
import moment from 'moment'
import { Toast } from 'react-native-toast-message/lib/src/Toast'
import { BackHandler } from 'react-native'
import { useEffect } from 'react'

const timer = [
    {
        id: 1,
        time: '12:00 PM'
    },
    {
        id: 2,
        time: '02:00 PM'
    },
    {
        id: 3,
        time: '04:00 PM'
    },

]
const AddPropertyScheduleDate = (props) => {
    const [date, setDate] = useState(new Date())

    const [selected, setSelected] = useState(new Date());
    const [bgColor, setBgColor] = useState('blue');
    const [textColor, setTextColor] = useState('red');
    const [isSelected, setIsSelected] = useState(true)
    const [time, setTime] = useState(0)

    const onDayPress = useCallback((day) => {
        setSelected(day.dateString);
        setBgColor('#F1592A');
        setTextColor('white');
    }, []);


    const markedDates = useMemo(() => {
        return {
            [selected]: {
                selected: true,
                disableTouchEvent: true,
                selectedColor: bgColor,
                selectedTextColor: textColor
            },
            ['2022-07-22']: {
                dotColor: 'red',
                marked: true
            }
        };
    }, [selected]);

    const onClickScheduleDateandTime = () => {
        if (time == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please select time slot'
            })
        } else {
            props.navigation.navigate('AddPropertyScheduleDetails', { DATE: selected, TIME: time })

        }
    }

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
    // console.log('seeleljlee', date);

    const renderArrow = (direction) => {
        if (direction === 'left') {
            return <Image source={Images.ArrowIcons} style={{ tintColor: '#707070', transform: [{ rotate: '185deg' }] }} />
        } else {
            return <Image source={Images.ArrowIcons} style={{ tintColor: '#707070', transform: [{ rotate: '3deg' }] }} />
        }
    }
    return (
        <View style={styles.container}>
            <Header Heading={'Schedule Date & Time'} onPress={() => props.navigation.navigate('BecomeHost')} />
            <ScrollView showsVerticalScrollIndicator={false}>
                <View style={{ marginHorizontal: 20 }}>
                    <Text style={{ fontSize: 20, color: '#000', fontWeight: '600', marginVertical: 15 }}>Select a Date</Text>
                    <View style={{ backgroundColor: '#F7F6FC', borderWidth: 1, borderRadius: 20, borderColor: '#F7F6FC', height: 330, width: '100%' }}>
                        <Calendar
                            // minDate={'2010-05-10'}
                            minDate={moment(date).format('YYYY-MM-DD')}
                            maxDate={'2030-05-30'}
                            // onDayPress={day => {
                            //     console.log('selected day', day.dateString);
                            //     setDate( day.dateString.toString())

                            //   }}
                            hideDayNames={true}
                            theme={{
                                //    dayTextColor:'red',
                                selectedDayBackgroundColor: 'orange',
                                //    backgroundColor:'red',
                                calendarBackground: '#F7F6FC',
                                arrowStyle: { marginHorizontal: -10 },
                                textDayHeaderFontWeight: 'bold',
                                //   contentStyle:{borderRadius:20,borderWidth:2}



                            }}
                            style={{
                                // borderWidth: 1,
                                // borderColor: '#000',
                                borderRadius: 20,
                                // height:300,
                                // width:'100%'
                            }}
                            renderArrow={renderArrow}
                            onDayPress={onDayPress}
                            markedDates={markedDates}

                        />
                       
                    </View>
                    <View>
                        <Text style={{ fontSize: 20, color: '#000', fontWeight: '600', marginTop: 14 }}>Select a Time</Text>
                        <Text style={{ fontSize: 15, color: '#000', marginTop: 5, fontWeight: '600', marginBottom: 10 }}>
                            
                            {moment(selected).format('YYYY-MM-DD')}
                        </Text>
                      
                    </View>
                    {
                        timer.map((item, index) => (
                            <TouchableOpacity onPress={() => { setTime(item.time), setIsSelected(index) }} style={[styles.timer, { backgroundColor: isSelected !== index ? '#F7F6FC' : '#FFEEDC', borderColor: isSelected !== index ? '#00000029' : '#F99428' }]}>
                                <Text style={{ fontSize: 18, color: isSelected !== index ? '#3C317D' : '#F1592A' }}>{item.time}</Text>
                            </TouchableOpacity>
                        ))
                    }
                    <TouchableOpacity onPress={() =>
                        onClickScheduleDateandTime()
                    }
                        style={{
                            marginTop: 20,
                            backgroundColor: color.appOrangeColor,
                            width: width - 30,
                            padding: 10,
                            borderRadius: 10,
                            justifyContent: 'center',
                            alignSelf: 'center',
                            height: 50
                        }}>
                        <View style={{
                            position: 'absolute',
                            flex: 1,
                        }}>
                            <Image source={Images.whiteDot} style={{
                                paddingLeft: 70,
                                width: 25, height: 25, resizeMode: 'contain',
                            }} />
                        </View>

                        <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Confirm</Text>
                    </TouchableOpacity>
                    <View style={{ marginBottom: 20 }} />
                </View>
            </ScrollView>
        </View>
    )
}

export default AddPropertyScheduleDate

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#FFFFFF'
    },
    timer: {
        height: 55,
        width: '100%',
        borderWidth: 1,
        borderRadius: 10,
        justifyContent: 'center',
        alignItems: 'center',
        marginVertical: 8,
        borderColor: '#F99428'
    }
})