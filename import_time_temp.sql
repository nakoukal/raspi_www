select concat("INSERT IGNORE INTO temp_day(sensorID,max_val,max_timekey,min_val,min_timekey,avg,day) select t1.sensorID, t1.max_val,t1.max_timekey,t2.min_val,t2.min_Timekey,t3.avg,t3.day from
  (select 'D64FB4010000' sensorID ,temp02 max_val,unix_timestamp(timestamp) max_timekey from temp where timestamp between '",min(timestamp),"' and '", max(timestamp),"' order by temp02 DESC limit 1) t1 
join 
  (select 'D64FB4010000' sensorID ,temp02 min_val,unix_timestamp(timestamp) min_timekey from temp where timestamp between '",min(timestamp),"' and '", max(timestamp),"' order by temp02  limit 1) t2 on t1.sensorID=t2.sensorID
join 
  (select 'D64FB4010000' sensorID ,round(avg(temp02),1) avg, day from temp where timestamp between '",min(timestamp),"' and '", max(timestamp),"') t3 on t1.sensorID=t3.sensorID;") from temp group by day;
