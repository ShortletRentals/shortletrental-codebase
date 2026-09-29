import React, {useState} from 'react';
import CheckBox from '@react-native-community/checkbox';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TouchableOpacity,
  StyleSheet,
  _Text,
  ScrollView,
} from 'react-native';
import {color, height, width} from '../../styles/colors';
import Images from '../../styles/Images';
import {commonStyles} from './../../styles/style';
import {Colors, colors} from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import {Dropdown} from 'react-native-element-dropdown';

const data = [
  {label: 'Item 1', value: '1'},
  {label: 'Item 2', value: '2'},
  {label: 'Item 3', value: '3'},
  {label: 'Item 4', value: '4'},
  {label: 'Item 5', value: '5'},
  {label: 'Item 6', value: '6'},
  {label: 'Item 7', value: '7'},
  {label: 'Item 8', value: '8'},
];

const BookingFill = props => {
  const [value, setValue] = useState(null);
  const [toggleCheckBox, setToggleCheckBox] = useState(false);
  const [toggleCheckBox1, setToggleCheckBox1] = useState(false);
  return (
    <View style={{backgroundColor: color.appWhiteColor, flex: 1}}>
      <Header
      
        props={props}
        Heading={'Booking'}
        onPress={() => props.navigation.navigate('Booking')}
      />
      <ScrollView showsVerticalScrollIndicator={false}>
        <View style={{marginHorizontal: 20}}>
          <View
            style={{
              width: '100%',
              height: height / 5,
              borderColor: color.appTextBackgoundColor,
              alignSelf: 'center',
            }}>
            <Text
              style={{
                marginTop: 10,
                color: color.primaryColorBlack,
                fontWeight: '500',
              }}>
              YOUR TRIP
            </Text>
            <View
              style={{
                width: '100%',
                height: 140,
                borderRadius: 10,
                borderWidth: 1,
                borderColor: '#e1e1e1',
                alignSelf: 'center',
                backgroundColor: color.appWhiteColor,
                marginTop: 5,
              }}>
              <View
                style={{
                  width: '100%',
                  height: 70,
                  alignSelf: 'center',
                  borderRadius: 10,
                  borderWidth: 1,
                  backgroundColor: color.appWhiteColor,
                  borderColor: '#e1e1e1',
                  flexDirection: 'row',
                }}>
                <View
                  style={{
                    backgroundColor: color.appWhiteColor,
                    flex: 0.5,
                    paddingLeft: 16,
                    borderRightWidth: 1,
                    borderRightColor: '#cdc6c6',
                    justifyContent: 'center',
                    borderBottomLeftRadius: 10,
                    borderBottomColor: '#cdc6c6',
                  }}>
                  <TouchableOpacity>
                    <Text
                      style={{
                        color: color.primaryColorBlack,
                        fontWeight: '500',
                      }}>
                      Check in
                    </Text>
                    <Text style={{color: color.appTextColor}}>21-09-232</Text>
                  </TouchableOpacity>
                </View>
                <View
                  style={{
                    backgroundColor: color.appWhiteColor,
                    borderBottomRightRadius: 10,
                    flex: 0.5,
                    paddingLeft: 16,
                    justifyContent: 'center',
                    borderBottomColor: '#cdc6c6',
                  }}>
                  <TouchableOpacity>
                    <Text
                      style={{
                        color: color.primaryColorBlack,
                        fontWeight: '500',
                      }}>
                      Check out
                    </Text>
                    <Text style={{color: color.appTextColor}}>16-08-2022</Text>
                  </TouchableOpacity>
                </View>
              </View>

              <View style={{paddingLeft: 16}}>
                <Text
                  style={{
                    color: color.primaryColorBlack,
                    marginTop: 8,
                    fontWeight: '500',
                  }}>
                  Who
                </Text>
                <Dropdown
                  style={styles.dropdown}
                  // placeholderStyle={styles.placeholderStyle}
                  // selectedTextStyle={styles.selectedTextStyle}
                  // inputSearchStyle={styles.inputSearchStyle}
                  // iconStyle={styles.iconStyle}
                  data={data}
                  search={false}
                  maxHeight={300}
                  labelField="label"
                  valueField="value"
                  // placeholder=""
                  // searchPlaceholder="5 guests"
                  value={value}
                  
                  onChange={item => {
                    setValue(item.value);
                  }}
                />
              </View>
            </View>
            {/* <View style={{ width: width - 30, height: height / 7, borderRadius: 10, borderWidth: 0.5, borderColor: color.appTextBackgoundColor, alignSelf: 'center', backgroundColor: color.appWhiteColor }}>

                        <View style={{ width: width - 30, height: height / 13, alignSelf: 'center', borderRadius: 10, borderWidth: 1, backgroundColor: color.appWhiteColor, borderColor: color.appTextBackgoundColor, flexDirection: 'row' }}>
                            <View style={{ backgroundColor: color.appWhiteColor, marginLeft: 10, flex: 0.5, borderRightWidth: 0.5, borderRightColor: color.appTextColor, justifyContent: 'center' }}>
                                <TouchableOpacity>
                                    <Text style={{color:color.primaryColorBlack}}>Check in</Text>
                                    <Text style={{ color: color.appTextColor }}>21-09-232</Text>
                                </TouchableOpacity>
                            </View>
                            <View style={{ backgroundColor: color.appWhiteColor, flex: 0.5, marginRight: 10, marginLeft: 10, justifyContent: 'center' }}>
                                <TouchableOpacity>
                                    <Text style={{color:color.primaryColorBlack}}>Check Out</Text>
                                    <Text style={{ color: color.appTextColor }}>16-08-2022</Text>
                                </TouchableOpacity>

                            </View>
                        </View>
                        <View style={{ height: height / 13, width: width - 30, justifyContent: 'space-between' }}>
                            <View style={{ paddingLeft: 14, justifyContent: 'center' }}>
                                <Text style={{ fontWeight: '400',color:color.primaryColorBlack }}>Who</Text>
                                <Dropdown
                                    style={styles.dropdown}
                                    placeholderStyle={styles.placeholderStyle}
                                    selectedTextStyle={styles.selectedTextStyle}
                                    inputSearchStyle={styles.inputSearchStyle}
                                    iconStyle={styles.iconStyle}
                                    data={data}
                                    search={false}
                                    maxHeight={300}
                                    labelField="label"
                                    valueField="value"
                                    placeholder="5 Guests"
                                    // searchPlaceholder="5 guests"
                                    value={value} s
                                    onChange={item => {
                                        setValue(item.value);
                                    }} />
                            </View>
                        </View>
                    </View> */}
          </View>
        </View>
        <View
          style={{
            borderBottomWidth: 1,
            marginTop: 35,
            borderBottomColor: '#e1e1e1',
          }}
        />
        <View style={{marginHorizontal: 20}}>
          <View
            style={{
              width: '100%',
              height: height / 4,
              borderBottomWidth: 2,
              borderColor: color.appTextBackgoundColor,
              marginTop: 5,
            }}>
            <Text
              style={{
                marginTop: 10,
                fontSize: 15,
                color: color.primaryColorBlack,
                fontWeight: '500',
              }}>
              PRICE DETAIL
            </Text>
            <View
              style={{
                justifyContent: 'space-between',
                flexDirection: 'row',
                marginTop: width * (10 / 375),
              }}>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.appTextColor,
                }}>
                NGN 200.00 X 5 nights
              </Text>
              <Text
                style={{
                  fontSize: 15,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                }}>
                NGN 1000.00
              </Text>
            </View>

            <View
              style={{
                justifyContent: 'space-between',
                flexDirection: 'row',
                marginTop: width * (2 / 375),
              }}>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.appTextColor,
                }}>
                Service fee
              </Text>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.primaryColorBlack,
                }}>
                NGN 100.00
              </Text>
            </View>
            <View
              style={{
                justifyContent: 'space-between',
                flexDirection: 'row',
                marginTop: width * (2 / 375),
              }}>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.appTextColor,
                }}>
                Discount
              </Text>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.primaryColorBlack,
                }}>
                NGN 10.00
              </Text>
            </View>
            <View
              style={{
                justifyContent: 'space-between',
                flexDirection: 'row',
                marginTop: width * (2 / 375),
              }}>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.appTextColor,
                }}>
                Extra Amount
              </Text>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.primaryColorBlack,
                }}>
                NGN 100.00
              </Text>
            </View>

            <View
              style={{
                justifyContent: 'space-between',
                flexDirection: 'row',
                marginTop: width * (2 / 375),
              }}>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.appTextColor,
                }}>
                Total
              </Text>
              <Text
                style={{
                  fontWeight: '500',
                  fontSize: 15,
                  color: color.primaryColorBlack,
                }}>
                NGN 1200.00
              </Text>
            </View>
            <TouchableOpacity
              onPress={() => props.navigation.navigate('Payment')}
              style={{
                marginTop: 20,
                backgroundColor: color.appBlueColor,
                width: '100%',
                padding: 10,
                borderRadius: 10,
                justifyContent: 'center',
                // marginLeft: 15,
                height: 50,
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
                    width: 25,
                    height: 25,
                    resizeMode: 'contain',
                  }}
                />
              </View>

              <Text
                style={{
                  textAlign: 'center',
                  fontSize: 15,
                  color: color.appWhiteColor,
                }}>
                Pay Now
              </Text>
            </TouchableOpacity>
          </View>
        </View>
        <View
          style={{
            marginTop: 45,
            borderBottomWidth: 1,
            borderBottomColor: '#e1e1e1',
          }}
        />
        <View
          style={{
            width: width,
            height: height / 6,
            borderBottomWidth: 2,
            borderColor: color.appTextBackgoundColor,
            paddingLeft: 15,
            paddingRight: 15,
          }}>
          <Text
            style={{
              fontSize: 15,
              marginTop: 12,
              color: color.primaryColorBlack,
              fontWeight: '500',
            }}>
            OPTIONAL SERVICES
          </Text>

          <View style={{flexDirection: 'row', marginTop: 10}}>
            <CheckBox
              disabled={false}
              value={toggleCheckBox}
              onValueChange={newValue => setToggleCheckBox(newValue)}
              tintColors={{true: '#F15927', false: '#C1C1C1'}}
              // onFillColor='#007aaf'
              onCheckColor="red"
              style={{
                height: 20,
                width: 20,
                transform: [{scaleX: 1.2}, {scaleY: 1.2}],
                borderRadius: 10,
              }}
            />
            <Text
              style={{marginHorizontal: 20, fontSize: 15, color: '#000000'}}>
              Early Check in/Late check Out (NGN 10,000.00 /booking)
            </Text>
          </View>
          <View style={{flexDirection: 'row', marginTop: 10}}>
            <CheckBox
              disabled={false}
              value={toggleCheckBox1}
              onValueChange={newValue => setToggleCheckBox1(newValue)}
              tintColors={{true: '#F15927', false: '#C1C1C1'}}
              // onFillColor='#007aaf'
              onCheckColor="red"
              style={{
                height: 20,
                width: 20,
                transform: [{scaleX: 1.2}, {scaleY: 1.2}],
                borderRadius: 10,
              }}
            />
            <Text style={{marginHorizontal: 20, fontSize: 15, color: '#000'}}>
              Video shoot (NGNN50,000.00 / booking)
            </Text>
          </View>
        </View>
        <View
          style={{
            borderBottomWidth: 0.4,
            borderBottomColor: color.appTextColor,
          }}
        />
        <View style={{width: width, height: height / 6}}>
          <View style={{width: width - 30, alignSelf: 'center'}}>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                marginTop: 10,
              }}>
              <Text
                style={{
                  fontSize: 15,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                }}>
                PERSONAL INFO
              </Text>
              <TouchableOpacity>
                <Text style={{color: color.appOrangeColor, fontSize: 15}}>
                  Edit
                </Text>
              </TouchableOpacity>
            </View>
            <Text
              style={{
                fontSize: 15,
                fontWeight: '500',
                color: color.appTextColor,
                marginTop: 5,
              }}>
              John doe
            </Text>
            <View
              style={{
                flexDirection: 'row',
                marginTop: 10,
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
              <Image
                source={Images.LocationIcon}
                style={{
                  width: 16,
                  height: 16,
                  resizeMode: 'contain',
                  marginRight: 6,
                }}
              />
              <Text style={{color: color.primaryColorBlack}}>
                123,lorem ipsum is dummy,302021
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                marginTop: 4,
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
              <Image
                source={Images.CallIcons}
                style={{
                  width: 16,
                  height: 16,
                  resizeMode: 'contain',
                  marginRight: 6,
                }}
              />
              <Text style={{color: color.primaryColorBlack}}>
                123,lorem ipsum is dummy,302021
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                marginTop: 4,
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
              <Image
                source={Images.messageIcons}
                style={{
                  width: 16,
                  height: 16,
                  resizeMode: 'contain',
                  marginRight: 6,
                }}
              />
              <Text style={{color: color.primaryColorBlack, marginLeft: 3}}>
                jphndoe@mailinator.com
              </Text>
            </View>
          </View>
        </View>
        <View
          style={{
            borderBottomWidth: 0.4,
            borderBottomColor: color.appTextColor,
            marginTop: 20,
          }}
        />
        <View>
          <View style={{width: width - 30, alignSelf: 'center'}}>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                marginTop: 10,
              }}>
              <Text
                style={{
                  fontSize: 15,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                }}>
                GUEST INFO
              </Text>
              <TouchableOpacity>
                <Text style={{color: color.appOrangeColor, fontSize: 15}}>
                  Edit
                </Text>
              </TouchableOpacity>
            </View>
            <Text
              style={{
                fontSize: 15,
                fontWeight: '500',
                color: color.appTextColor,
                marginTop: 4,
              }}>
              Peter Parker
            </Text>
            <View
              style={{
                flexDirection: 'row',
                marginTop: 10,
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
              <Image
                source={Images.LocationIcon}
                style={{
                  width: 16,
                  height: 16,
                  resizeMode: 'contain',
                  marginRight: 6,
                }}
              />
              <Text style={{color: color.primaryColorBlack}}>
                123,lorem ipsum is dummy,302021
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                marginTop: 4,
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
              <Image
                source={Images.CallIcons}
                style={{
                  width: 16,
                  height: 16,
                  resizeMode: 'contain',
                  marginRight: 6,
                }}
              />
              <Text style={{color: color.primaryColorBlack}}>
                123,lorem ipsum is dummy,302021
              </Text>
            </View>
            <View
              style={{
                flexDirection: 'row',
                marginTop: 4,
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
              <Image
                source={Images.messageIcons}
                style={{
                  width: 16,
                  height: 16,
                  resizeMode: 'contain',
                  marginRight: 6,
                }}
              />
              <Text style={{color: color.primaryColorBlack, marginLeft: 4}}>
                jphndoe@mailinator.com
              </Text>
            </View>
          </View>
          <View style={{marginBottom: 20}} />
        </View>
      </ScrollView>
    </View>
  );
};

const styles = StyleSheet.create({
  dropdown: {
    height: 50,
    // borderBottomColor: 'gray',
    // borderBottomWidth: 0.5,
    marginTop: -16,
    marginRight: 16,
  },
  icon: {
    marginRight: 5,
  },
  placeholderStyle: {
    fontSize: 16,
  },
  selectedTextStyle: {
    fontSize: 16,
  },
  iconStyle: {
    width: 20,
    height: 20,
  },
  inputSearchStyle: {
    height: 40,
    fontSize: 16,
    backgroundColor: 'red',
  },
});
export default BookingFill;
