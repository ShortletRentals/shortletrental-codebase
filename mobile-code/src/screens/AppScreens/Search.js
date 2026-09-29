import React, { useState, useCallback, useMemo } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TouchableOpacity, Modal, TextInput } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import { Calendar, CalendarList, Agenda } from 'react-native-calendars';
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import { ScrollView } from "react-native-gesture-handler";
// import { useCallback } from "react";
// import { useMemo } from "react";




const Search = (props) => {
  const [search, setSearch] = useState('');
  const [count1, setCount1] = useState(0)
  const [count2, setCount2] = useState(0)
  const [count3, setCount3] = useState(0)
  const [count4, setCount4] = useState(0)
  const counter1 = () => {
    if (count1 > 0) {
      setCount1(count1 - 1)
    }
  }
  const counter2 = () => {
    if (count2 > 0) {
      setCount2(count2 - 1)
    }
  }
  const counter3 = () => {
    if (count3 > 0) {
      setCount3(count3 - 1)
    }
  }
  const counter4 = () => {
    if (count4 > 0) {
      setCount4(count4 - 1)
    }
  }
  const [date, setDate] = useState(new Date())
  const [date1, setDate1] = useState(new Date())
  const [open, setOpen] = useState(false)
  const [modalVisible, setModalVisible] = useState(false)

  const [selected, setSelected] = useState(new Date());
  const [select, setSelect] = useState(new Date());
  const [bgColor, setBgColor] = useState('blue');
  const [bgColor1, setBgColor1] = useState('red');
  const [textColor, setTextColor] = useState('red');
  const [textColor1, setTextColor1] = useState('blue');

  const onDayPress = useCallback((day) => {
    setSelected(day.dateString);
    setBgColor('#F1592A');
    setTextColor('white');
  }, []);
  const onDayPress1 = useCallback((day1) => {
    setSelect(day1.dateString);
    // console.log('day1', day1.dateString);
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
      // ['2022-07-22']: {
      //   dotColor: 'red',
      //   marked: true
      // }
    };
  }, [selected]);
  // console.log('selected', selected);

  // const markedDates1 = useMemo(() => {
  //   return {
  //     [select]: {
  //       select: true,
  //       disableTouchEvent: true,
  //       selectedColor: bgColor1,
  //       selectedTextColor: textColor1
  //     },
  //     // ['2022-11-22']: {
  //     //   dotColor: 'red',
  //     //   marked: true
  //     // }
  //   };
  // }, [select]);
  return (
    <View style={commonStyles.container}>
      <Header props={props} Heading={'Search'} onPress={() => props.navigation.navigate('Home')} />
      <ScrollView>
        <Text style={{ marginTop: 10, color: color.primaryColorBlack, marginLeft: 40 }}>Where</Text>
        {/* <TextView style={{ marginTop: 0 }}
          placeholder={' Search destinations'}
          value={Search}
          onChangeText={(e) => setName(e)}
          
        /> */}
        <TextInput
          placeholder="Search destinations"
          value={search}
          onChangeText={(e) => setSearch(e)}
          style={styles.input1}
          keyboardType='email-address'

        />
        <Text style={{ marginTop: 4, marginLeft: 40, color: color.primaryColorBlack }}>Check In</Text>
        <View
          style={{
            //  backgroundColor: '#fff',
            // justifyContent:'center',alignItems:'center',
            marginHorizontal: 18,
            marginTop: 10
          }}
        >
          <Calendar
            // Initially visible month. Default = now
            initialDate={selected}
            minDate={'2012-05-10'}
            maxDate={'2050-05-30'}
            onDayPress={onDayPress}
            // {day => {
            //   console.log('selected day', day);
            //   // setDate(day.dateString.toString())


            // }}
            markedDates={markedDates}
            // renderArrow={Images.ArrowIcons}
            // renderArrow={(direction) => direction === 'left' ? Images.ArrowIcons : Images.ArrowIcons}



          />


        </View>

        <Text style={{ marginTop: 20, marginLeft: 40, color: color.primaryColorBlack }}>Check Out</Text>
        <View style={{
          // backgroundColor: '#fff',
          // justifyContent:'center',
          // alignItems:'center',
          marginHorizontal: 18,
          marginTop: 10
        }}>
          <Calendar
            initialDate={select}
            minDate={'2012-05-10'}
            maxDate={'2050-05-30'}
            onDayPress={onDayPress1}

            markingType={'custom'}
            markedDates={{
              [select] : {
                customStyles: {
                  container: {
                    backgroundColor: '#F1592A'
                  },
                  text: {
                    color: 'white',
                    // fontWeight: 'bold'
                  }
                }
              } 
             
            }}

          // {day => {
          //   console.log('selected day', day);
          //   setDate1(day.dateString.toString())
          // }}

          />
        </View>
        <Text style={{ fontWeight: '400', marginLeft: 40, color: color.primaryColorBlack, padding: 6, marginTop: 20 }}>Who</Text>
        <View style={{ backgroundColor: color.appTextBackgoundColor, borderRadius: 10, marginHorizontal: 20, marginVertical: 10 }}  >
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 18, backgroundColor: color.appTextBackgoundColor }}>
            <View style={{ marginLeft: 10, marginTop: 10 ,width:200}}>
              <Text style={{ fontSize: 15, fontWeight: '400', color: color.primaryColorBlack }}>Adults</Text>
              <Text style={{ fontSize: 15, fontWeight: '400' }}>Ages 13 or above</Text>
            </View>
            <View style={{ flexDirection: 'row', marginTop: 10, marginRight: 10 }}>
              <TouchableOpacity activeOpacity={0.9} onPress={counter1} style={styles.AddingBackground}>
                <Image source={Images.minusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
              <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center' }}><Text style={{ color: color.primaryColorBlack, fontWeight: '500' }}>{count1}</Text></View>
              <TouchableOpacity activeOpacity={0.9} onPress={() => setCount1(count1 + 1)} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                <Image source={Images.plusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
            </View>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, backgroundColor: color.appTextBackgoundColor }}>
            <View style={{ marginLeft: 10, marginTop: 10,width:200 }}>
              <Text style={{ fontSize: 15, fontWeight: '400', color: color.primaryColorBlack }}>Children</Text>
              <Text style={{ fontSize: 15, fontWeight: '400' }}>Ages 2 t- 12</Text>
            </View>
            <View style={{ flexDirection: 'row', marginTop: 10, marginRight: 10 }}>
              <TouchableOpacity activeOpacity={0.9} onPress={counter2} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack, fontSize: 20 }}>-</Text> */}
                <Image source={Images.minusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
              <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center' }}><Text style={{ color: color.primaryColorBlack, fontWeight: '500' }}>{count2}</Text></View>
              <TouchableOpacity activeOpacity={0.9} onPress={() => setCount2(count2 + 1)} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                <Image source={Images.plusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
            </View>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, backgroundColor: color.appTextBackgoundColor }}>
            <View style={{ marginLeft: 10, marginTop: 10,width:200 }}>
              <Text style={{ fontSize: 15, fontWeight: '400', color: color.primaryColorBlack }}>Infants</Text>
              <Text style={{ fontSize: 15, fontWeight: '400' }}>Under 2</Text>
            </View>
            <View style={{ flexDirection: 'row', marginTop: 10, marginRight: 10 }}>
              <TouchableOpacity activeOpacity={0.9} onPress={counter3} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack, fontSize: 20 }}>-</Text> */}
                <Image source={Images.minusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
              <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center' }}><Text style={{ color: color.primaryColorBlack, fontWeight: '500' }}>{count3}</Text></View>
              <TouchableOpacity activeOpacity={0.9} onPress={() => setCount3(count3 + 1)} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                <Image source={Images.plusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
            </View>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, backgroundColor: color.appTextBackgoundColor }}>
            <View style={{ marginLeft: 10, marginTop: 10 ,width:200}}>
              <Text style={{ fontSize: 15, fontWeight: '400', color: color.primaryColorBlack }}>Pets</Text>
              <Text style={{ fontSize: 15, fontWeight: '400' ,}}>Bringing a service animal?</Text>
            </View>
            <View style={{ flexDirection: 'row', marginTop: 10, marginRight: 10, marginBottom: 15 }}>
              <TouchableOpacity activeOpacity={0.5} onPress={counter4} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack, fontSize: 20 }}>-</Text> */}
                <Image source={Images.minusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
              <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center' }}><Text style={{ color: color.primaryColorBlack, fontWeight: '500' }}>{count4}</Text></View>
              <TouchableOpacity activeOpacity={0.9} onPress={() => setCount4(count4 + 1)} style={styles.AddingBackground}>
                {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                <Image source={Images.plusIcon} style={{ height: 20, width: 20 }} />
              </TouchableOpacity>
            </View>
          </View>
        </View>
        <TouchableOpacity
          //  onPress={() => setModalVisible(!modalVisible)} 
          style={{
            marginTop: 10,
            backgroundColor: color.appBlueColor,
            width: '90%',
            padding: 10,
            borderRadius: 10,
            justifyContent: 'center',
            // marginLeft: 15,
            marginHorizontal: 20,
            marginBottom: 30,
            height: 50,

          }}>
          <View style={{
            position: 'absolute',
            flex: 1,
          }}>
            <Image source={Images.whiteDot} style={{
              paddingLeft: 50,
              width: 25, height: 25, resizeMode: 'contain',
            }} />
          </View>

          <Text style={{ textAlign: "center", fontSize: 14, color: color.appWhiteColor }}>Search</Text>
        </TouchableOpacity>

        {/* <View style={{flex:1,justifyContent:'center',alignItems:'center'}} >
          <Modal
            visible={modalVisible}
            transparent={true}
            // onRequestClose={() => {
            //   Alert.alert("Modal has been closed.");
            //   setModalVisible(!modalVisible);
            // }}
            style={{flex:1,justifyContent:'center',marginTop:100}}
          >
            <Text style={{fontSize:20,color:'black',marginTop:20,}}>Saved Success...</Text>
            <TouchableOpacity style={{height:20,width:50,backgroundColor:'white',borderWidth:1,borderRadius:20,justifyContent:'center',alignItems:'center'}} onPress={()=>[props.navigation.navigate('Home'),setModalVisible(true)]}>
              <Text>Ok</Text>
            </TouchableOpacity>
          </Modal>
        </View> */}
      </ScrollView>
    </View>
  )
}

const styles = StyleSheet.create({
  input: {
    height: 40,
    margin: 12,
    padding: 20,
    backgroundColor: color.appTextBackgoundColor,
    borderRadius: 4
  },
  container: {
    marginRight: 10, fontSize: 20, color: color.appWhiteColor, fontWeight: '600',
  },
  AddingBackground: { height: 30, width: 30, borderRadius: 15, backgroundColor: color.appAddingColor, alignItems: 'center', justifyContent: 'center' },
  input1: {
    paddingLeft: 20,
    height: 50,
    margin: 10,
    backgroundColor: color.appTextBackgoundColor,
    borderRadius: 10,
    marginLeft: 20,
    marginRight: 20,
    fontSize: 15,

  },

});

export default Search;