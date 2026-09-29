import { Dimensions } from "react-native";

var color = {
   primaryColorBlack: "#000000",
   primaryColorGray: "#6B6868",
   white: "white",
   inputBoxBorderGray:"#CCCCCC",
   red:"red",
   greenColor:'#00A651',
   cardColor:'#F2F2F2',
   lightGray: '#F1F1F1',
   appYellowColor: '#F99428',
   appOrangeColor: '#F1592A',
   appWhiteColor: '#FFFFFF',
   appBlueColor: '#3C317D',
   appTextBackgoundColor:"#F7F6FC",
   appTextColor:"#777777",
   appLightOrangeColor:'#FFEEDC',
   appAddingColor:'#E4E4E4'
}

var { width, height } = Dimensions.get('window');

var margin = {
   marginLeft: width * (20 / 375),
   marginRight: width * (20 / 375),
   marginTop: width * (20 / 375)
}

export { color, width, height, margin }